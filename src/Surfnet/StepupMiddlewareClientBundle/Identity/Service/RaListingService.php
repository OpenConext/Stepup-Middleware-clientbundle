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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Service;

use Surfnet\StepupMiddlewareClient\Identity\Dto\RaListingSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\RaListingService as LibraryRaListingService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaListing;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaListingCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Provides access to the Middleware API resources.
 */
class RaListingService
{
    /**
     * @var LibraryRaListingService
     */
    private $service;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param LibraryRaListingService $service
     * @param ValidatorInterface      $validator
     */
    public function __construct(LibraryRaListingService $service, ValidatorInterface $validator)
    {
        $this->service   = $service;
        $this->validator = $validator;
    }

    /**
     * @param string $id
     * @param string $institution
     * @return null|RaListing
     */
    public function get($id, $institution)
    {
        $data = $this->service->get($id, $institution);

        if ($data === null) {
            return null;
        }

        $raListing = RaListing::fromData($data);
        $message = sprintf("RaListing '%s' retrieved from the Middleware is invalid", $id);
        $this->assertIsValid($raListing, $message);

        return $raListing;
    }

    /**
     * @param RaListingSearchQuery $searchQuery
     * @return RaListingCollection
     */
    public function search(RaListingSearchQuery $searchQuery)
    {
        $data = $this->service->search($searchQuery);

        if ($data === null) {
            throw new InvalidResponseException(
                'Received a "null" as data when searching for RaListings, is the library service set up correctly?'
            );
        }

        $registrationAuthorities = RaListingCollection::fromData($data);

        $this->assertIsValid(
            $registrationAuthorities,
            'One or more registration authority listings retrieved from the Middleware were invalid'
        );

        return $registrationAuthorities;
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
