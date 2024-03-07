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

use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;
use Surfnet\StepupMiddlewareClient\Identity\Dto\RaSecondFactorExportQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\RaSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\RaSecondFactorService as LibraryRaSecondFactorService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaSecondFactorCollection;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaSecondFactorExportCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RaSecondFactorService
{
    public function __construct(private readonly LibraryRaSecondFactorService $service, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function search(RaSecondFactorSearchQuery $query): ?RaSecondFactorCollection
    {
        $data = $this->service->search($query);

        if ($data === null) {
            return null;
        }

        $secondFactors = RaSecondFactorCollection::fromData($data);
        $violations = $this->validator->validate($secondFactors);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "One or more second factors retrieved from the Middleware were invalid",
                $violations
            );
        }

        return $secondFactors;
    }
    /**
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchForExport(RaSecondFactorExportQuery $query): ?RaSecondFactorExportCollection
    {
        $data = $this->service->searchForExport($query);

        if ($data === null) {
            return null;
        }

        $secondFactors = RaSecondFactorExportCollection::fromData($data);
        $violations = $this->validator->validate($secondFactors);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "One or more second factors retrieved from the Middleware were invalid",
                $violations
            );
        }

        return $secondFactors;
    }
}
