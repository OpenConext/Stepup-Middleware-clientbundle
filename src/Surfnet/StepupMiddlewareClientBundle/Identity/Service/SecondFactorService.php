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

use Surfnet\StepupBundle\Service\SecondFactorTypeService;
use Surfnet\StepupBundle\Value\SecondFactorType;
use Surfnet\StepupBundle\Value\VettingType;
use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;
use Surfnet\StepupMiddlewareClient\Identity\Dto\UnverifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VerifiedSecondFactorOfIdentitySearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VerifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VettedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\SecondFactorService as LibrarySecondFactorService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\UnverifiedSecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\UnverifiedSecondFactorCollection;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VerifiedSecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VerifiedSecondFactorCollection;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VettedSecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VettedSecondFactorCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SecondFactorService
{
    public function __construct(
        private readonly LibrarySecondFactorService $service,
        private readonly SecondFactorTypeService $loaService,
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * @param string $secondFactorId
     * @return UnverifiedSecondFactor|null
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function getUnverified($secondFactorId): ?\Surfnet\StepupMiddlewareClientBundle\Identity\Dto\UnverifiedSecondFactor
    {
        $data = $this->service->getUnverified($secondFactorId);

        if ($data === null) {
            return null;
        }

        $secondFactor = UnverifiedSecondFactor::fromData($data);
        $violations = $this->validator->validate($secondFactor);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "Unverified second factor retrieved from the Middleware was invalid",
                $violations
            );
        }

        return $secondFactor;
    }

    /**
     * @param string $secondFactorId
     * @return VerifiedSecondFactor|null
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function getVerified($secondFactorId): ?\Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VerifiedSecondFactor
    {
        $data = $this->service->getVerified($secondFactorId);

        if ($data === null) {
            return null;
        }

        $secondFactor = VerifiedSecondFactor::fromData($data);
        $violations = $this->validator->validate($secondFactor);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "Verified second factor retrieved from the Middleware was invalid",
                $violations
            );
        }

        return $secondFactor;
    }

    /**
     * @param string $secondFactorId
     * @return bool|null
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function getVerifiedCanSkipProvePossession($secondFactorId): bool
    {
        $data = $this->service->getVerifiedCanSkipProvePossession($secondFactorId);

        return (bool)$data;
    }

    /**
     * @param string $secondFactorId
     * @return VettedSecondFactor|null
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function getVetted($secondFactorId): ?\Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VettedSecondFactor
    {
        $data = $this->service->getVetted($secondFactorId);

        if ($data === null) {
            return null;
        }

        $this->setLoaOnData($data, $this->loaService);

        $secondFactor = VettedSecondFactor::fromData($data);
        $violations = $this->validator->validate($secondFactor);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "Vetted second factor retrieved from the Middleware was invalid",
                $violations
            );
        }

        return $secondFactor;
    }

    /**
     * @return UnverifiedSecondFactorCollection
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchUnverified(UnverifiedSecondFactorSearchQuery $query)
    {
        $data = $this->service->searchUnverified($query);

        if ($data === null) {
            return null;
        }

        $secondFactors = UnverifiedSecondFactorCollection::fromData($data);
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
     * @return VerifiedSecondFactorCollection
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchVerified(VerifiedSecondFactorSearchQuery $query)
    {
        $data = $this->service->searchVerified($query);

        if ($data === null) {
            return null;
        }

        $secondFactors = VerifiedSecondFactorCollection::fromData($data);
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
     * @return VerifiedSecondFactorCollection
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchOwnVerified(VerifiedSecondFactorOfIdentitySearchQuery $query)
    {
        $data = $this->service->searchOwnVerified($query);

        if ($data === null) {
            return null;
        }

        $secondFactors = VerifiedSecondFactorCollection::fromData($data);
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
     * @return VettedSecondFactorCollection
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchVetted(VettedSecondFactorSearchQuery $query)
    {
        $data = $this->service->searchVetted($query);

        if ($data === null) {
            return null;
        }

        foreach ($data['items'] as &$tokenData) {
            $this->setLoaOnData($tokenData, $this->loaService);
        }

        $secondFactors = VettedSecondFactorCollection::fromData($data);
        $violations = $this->validator->validate($secondFactors);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "One or more second factors retrieved from the Middleware were invalid",
                $violations
            );
        }

        return $secondFactors;
    }

    private function setLoaOnData(array &$data, SecondFactorTypeService $loaService): void
    {
        $loaLevel = $loaService->getLevel(
            new SecondFactorType($data['type']),
            new VettingType($data['vetting_type'])
        );
        $data['loa_level'] = $loaLevel;
    }
}
