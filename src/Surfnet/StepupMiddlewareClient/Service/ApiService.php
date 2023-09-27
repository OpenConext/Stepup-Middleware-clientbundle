<?php

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
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;
use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;
use Surfnet\StepupMiddlewareClient\Helper\JsonHelper;

/**
 * Provides remote read access to the Middleware's API.
 */
class ApiService
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @param Client $guzzleClient A Guzzle client preconfigured with base URL and proper authentication.
     */
    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param string $path A URL path, optionally containing printf parameters (e.g. '/a/b/%s/d'). The parameters
     *               will be URL encoded and formatted into the path string.
     *               Example: '/institution/%s/identity/%s', ['institution' => 'ab-cd', 'identity' => 'ef']
     * @param array $parameters An array containing the parameters to replace in the path.
     * @param HttpQuery $httpQuery|null
     * @return null|mixed Most likely an array structure, null when the resource doesn't exist.
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function read($path, array $parameters = [], HttpQuery $httpQuery = null)
    {
        $resource = $this->buildResourcePath($path, $parameters, $httpQuery);

        $response = $this->guzzleClient->get($resource, ['http_errors' => false]);
        $statusCode = $response->getStatusCode();

        try {
            $body = $response->getBody()->getContents();
            $data = JsonHelper::decode($body);
            $errors = isset($data['errors']) && is_array($data['errors']) ? $data['errors'] : [];
        } catch (\RuntimeException $e) {
            // Malformed JSON body
            throw new MalformedResponseException('Cannot read resource: Middleware returned malformed JSON');
        }

        if ($statusCode == 404) {
            return null;
        }

        if ($statusCode == 403) {
            // Consumer requested resource it is not authorised to access.
            throw new AccessDeniedToResourceException($resource, $errors);
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new ResourceReadException(
                sprintf('Resource could not be read (status code %d)', $statusCode),
                $errors
            );
        }

        return $data;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param HttpQuery|null $httpQuery
     * @return string
     */
    private function buildResourcePath($path, array $parameters, HttpQuery $httpQuery = null)
    {
        if (count($parameters) > 0) {
            $resource = vsprintf($path, array_map('urlencode', $parameters));
        } else {
            $resource = $path;
        }

        if (empty($resource)) {
            throw new RuntimeException(
                sprintf(
                    'Could not construct resource path from path "%s", parameters "%s" and search query "%s"',
                    $path,
                    implode('","', $parameters),
                    $httpQuery ? $httpQuery->toHttpQuery() : ''
                )
            );
        }

        if ($httpQuery !== null) {
            $resource .= $httpQuery->toHttpQuery();
        }

        return $resource;
    }
}
