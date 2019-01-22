<?php

/**
 * Copyright 2019 SURFnet B.V.
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

use Surfnet\StepupMiddlewareClient\Identity\Dto\ProfileSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\ProfileService as LibraryProfileService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Profile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileService
{
    /**
     * @var LibraryProfileService
     */
    private $service;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param LibraryProfileService $service
     * @param ValidatorInterface $validator
     */
    public function __construct(LibraryProfileService $service, ValidatorInterface $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }
    /**
     * @param string $identityId
     * @return null|Profile
     */
    public function get($identityId)
    {
        $query = new ProfileSearchQuery($identityId, $identityId);
        $data = $this->service->get($query);

        if ($data === null) {
            return null;
        }

        $profile = Profile::fromData($data);

        $message = sprintf("Profile '%s' retrieved from the Middleware is invalid", $identityId);
        $this->assertIsValid($profile, $message);

        return $profile;
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
