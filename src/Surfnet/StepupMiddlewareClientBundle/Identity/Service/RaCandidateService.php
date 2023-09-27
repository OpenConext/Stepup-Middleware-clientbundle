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

use Surfnet\StepupMiddlewareClient\Identity\Dto\RaCandidateSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\RaCandidateService as LibraryRaCandidateService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaCandidateCollection;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaCandidateInstitutions;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RaCandidateService
{
    public function __construct(private readonly LibraryRaCandidateService $libraryService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @return RaCandidateCollection
     */
    public function search(RaCandidateSearchQuery $query)
    {
        $data = $this->libraryService->search($query);

        if ($data === null) {
            throw new InvalidResponseException(
                'Received a "null" as data when searching for RaCandidates, is the library service set up correctly?'
            );
        }

        $collection = RaCandidateCollection::fromData($data);

        $this->assertIsValid($collection, 'One or more RaCandidates are not valid');

        return $collection;
    }

    /**
     * @param string $identityId
     * @param string $institution
     * @param string $actorId
     * @return RaCandidateInstitutions
     */
    public function get($identityId, $actorId): ?\Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaCandidateInstitutions
    {
        $data = $this->libraryService->get($identityId, $actorId);

        if ($data === null) {
            return null;
        }

        $raCandidateInstitutions = RaCandidateInstitutions::fromData($data);

        $this->assertIsValid($raCandidateInstitutions, 'Received invalid RaCandidate');

        return $raCandidateInstitutions;
    }

    private function assertIsValid(mixed $value, string $message): void
    {
        $violations = $this->validator->validate($value);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations($message, $violations);
        }
    }
}
