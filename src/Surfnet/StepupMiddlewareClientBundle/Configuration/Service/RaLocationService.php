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

namespace Surfnet\StepupMiddlewareClientBundle\Configuration\Service;

use Surfnet\StepupMiddlewareClient\Configuration\Dto\RaLocationSearchQuery;
use Surfnet\StepupMiddlewareClient\Configuration\Service\RaLocationService as LibraryRaLocationService;
use Surfnet\StepupMiddlewareClientBundle\Configuration\Dto\RaLocation;
use Surfnet\StepupMiddlewareClientBundle\Configuration\Dto\RaLocationCollection;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Provides access to the Middleware API resources.
 */
class RaLocationService
{
    /**
     * @var LibraryRaLocationService
     */
    private $service;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param LibraryRaLocationService $service
     * @param ValidatorInterface      $validator
     */
    public function __construct(LibraryRaLocationService $service, ValidatorInterface $validator)
    {
        $this->service   = $service;
        $this->validator = $validator;
    }

    /**
     * @param string $id
     * @return null|RaLocation
     */
    public function get($id)
    {
        $data = $this->service->get($id);

        if ($data === null) {
            return null;
        }

        $raLocation = RaLocation::fromData($data);
        $message = sprintf("RaLocation '%s' retrieved from the Middleware is invalid", $id);
        $this->assertIsValid($raLocation, $message);

        return $raLocation;
    }

    /**
     * @param RaLocationSearchQuery $searchQuery
     * @return RaLocationCollection
     */
    public function search(RaLocationSearchQuery $searchQuery)
    {
        $data = $this->service->search($searchQuery);

        if ($data === null) {
            throw new InvalidResponseException(
                'Received a "null" as data when searching for RaLocations, is the library service set up correctly?'
            );
        }

        $registrationLocations = RaLocationCollection::fromData($data);

        $this->assertIsValid(
            $registrationLocations,
            'One or more registration authority locations retrieved from the Middleware were invalid'
        );

        return $registrationLocations;
    }

    /**
     * @param object      $value
     * @param null|string $message
     */
    private function assertIsValid($value, $message = null)
    {
        $violations = $this->validator->validate($value);

        $message = $message ?: 'Invalid Response Received';

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations($message, $violations);
        }
    }
}
