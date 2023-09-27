<?php

declare(strict_types = 1);

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupMiddlewareClient\Service;

use GuzzleHttp\Client;
use RuntimeException;
use Surfnet\StepupMiddlewareClient\Exception\CommandExecutionFailedException;
use Surfnet\StepupMiddlewareClient\Exception\InvalidArgumentException;
use Surfnet\StepupMiddlewareClient\Helper\JsonHelper;

class CommandService
{
    private readonly string $username;

    private readonly string $password;

    /**
     * @param Client $guzzleClient A Guzzle client preconfigured with the command URL.
     * @param string $username
     * @param string $password
     */
    public function __construct(private readonly Client $guzzleClient, $username, $password)
    {
        if (!is_string($username)) {
            throw InvalidArgumentException::invalidType('string', 'username', $username);
        }

        if (!is_string($password)) {
            throw InvalidArgumentException::invalidType('string', 'password', $password);
        }
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param string $commandName
     * @param string $uuid
     * @return ExecutionResult
     * @throws CommandExecutionFailedException
     */
    public function execute(mixed $commandName, $uuid, array $payload, array $metadata = []): \Surfnet\StepupMiddlewareClient\Service\ExecutionResult
    {
        $this->assertIsValidCommandName($commandName);
        if (!is_string($uuid)) {
            throw InvalidArgumentException::invalidType('string', 'uuid', $uuid);
        }

        $command = [
            'name' => $commandName,
            'uuid' => $uuid,
            'payload' => $payload,
        ];

        $body = ['command' => $command];

        if ($metadata !== []) {
            $body['meta'] = $metadata;
        }

        $requestOptions = [
            'json'        => $body,
            'http_errors' => false,
            'auth'        => [$this->username, $this->password, 'basic'],
            'headers'     => ['Accept' => 'application/json'],
        ];
        $httpResponse = $this->guzzleClient->post(null, $requestOptions);

        try {
            $response = JsonHelper::decode($httpResponse->getBody()->getContents());
        } catch (RuntimeException $e) {
            throw new CommandExecutionFailedException(
                'Server response could not be decoded as it isn\'t valid JSON.',
                0,
                $e
            );
        }

        return $httpResponse->getStatusCode() == 200
            ? $this->processOkResponse($uuid, $response)
            : $this->processErrorResponse($uuid, $response);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function assertIsValidCommandName(mixed $commandName): void
    {
        if (!is_string($commandName)) {
            InvalidArgumentException::invalidType('string', 'command', $commandName);
        }

        if (!preg_match('~^[a-z0-9_]+:([a-z0-9_].)*[a-z0-9_]+$~i', (string) $commandName)) {
            throw new InvalidArgumentException(
                'Command must be formatted AggregateRoot:Command or AggregateRoot:Name.Space.Command'
            );
        }
    }

    /**
     * @return ExecutionResult
     */
    private function processOkResponse(string $uuid, mixed $response): \Surfnet\StepupMiddlewareClient\Service\ExecutionResult
    {
        if (!isset($response['command'])) {
            throw new CommandExecutionFailedException('Unexpected response format: command key missing.');
        }

        if ($response['command'] !== $uuid) {
            throw new CommandExecutionFailedException(sprintf(
                'Unexpected response: returned command UUID "%s" does not match sent UUID "%s".',
                $response['command'],
                $uuid
            ));
        }

        if (!isset($response['processed_by'])) {
            throw new CommandExecutionFailedException('Unexpected response format: processed_by key missing.');
        }

        if (!is_string($response['processed_by'])) {
            throw new CommandExecutionFailedException(sprintf(
                'Unexpected response format: processed_by should be a string, "%s" given.',
                gettype($response['processed_by'])
            ));
        }

        return new ExecutionResult($uuid, $response['processed_by']);
    }

    /**
     * @return ExecutionResult
     */
    private function processErrorResponse(string $uuid, mixed $response): \Surfnet\StepupMiddlewareClient\Service\ExecutionResult
    {
        if (!isset($response['errors'])) {
            throw new CommandExecutionFailedException('Unexpected response format: errors key missing.');
        }

        if (!is_array($response['errors'])) {
            throw new CommandExecutionFailedException(sprintf(
                'Unexpected response format: errors should be an array, "%s" given.',
                gettype($response['errors'])
            ));
        }

        return new ExecutionResult($uuid, null, $response['errors']);
    }
}
