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

use Surfnet\StepupMiddlewareClient\Identity\Service\InstitutionListingService as LibraryInstitutionListingService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\InstitutionListingCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InstitutionListingService
{
    public function __construct(private readonly LibraryInstitutionListingService $clientService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @return InstitutionListingCollection
     */
    public function getAll(): \Surfnet\StepupMiddlewareClientBundle\Identity\Dto\InstitutionListingCollection
    {
        $data = $this->clientService->getAll();
        $collection = InstitutionListingCollection::fromData($data);

        $violations = $this->validator->validate($collection);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations('Invalid InstitutionListings received', $violations);
        }

        return $collection;
    }
}
