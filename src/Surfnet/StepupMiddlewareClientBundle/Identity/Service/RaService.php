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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Service;

use Surfnet\StepupMiddlewareClient\Identity\Service\RaService as LibraryRaService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RegistrationAuthorityCredentialsCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Provides access to the Middleware API resources.
 */
class RaService
{
    public function __construct(private readonly LibraryRaService $service, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @param string $institution
     * @return RegistrationAuthorityCredentialsCollection
     */
    public function listRas($institution)
    {
        $data = $this->service->listRas($institution);

        $collection = RegistrationAuthorityCredentialsCollection::fromData($data);

        $this->assertIsValid($collection, 'Invalid elements received in collection');

        return $collection;
    }

    /**
     * @param object      $value
     * @param null|string $message
     */
    private function assertIsValid(mixed $value, $message = null): void
    {
        $violations = $this->validator->validate($value);

        $message = $message ?: 'Invalid Response Received';

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations($message, $violations);
        }
    }
}
