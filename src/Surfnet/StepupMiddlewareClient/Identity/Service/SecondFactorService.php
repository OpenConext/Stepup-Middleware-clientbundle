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

namespace Surfnet\StepupMiddlewareClient\Identity\Service;

use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;
use Surfnet\StepupMiddlewareClient\Identity\Dto\UnverifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VerifiedSecondFactorOfIdentitySearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VerifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VettedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

/**
 * Provides remote read access to the Middleware's second factors.
 */
class SecondFactorService
{
    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @param string $secondFactorId
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function getUnverified($secondFactorId)
    {
        return $this->apiService->read('unverified-second-factor/%s', [$secondFactorId]);
    }

    /**
     * @param string $secondFactorId
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function getVerified($secondFactorId)
    {
        return $this->apiService->read('verified-second-factor/%s', [$secondFactorId]);
    }


    /**
     * @param string $secondFactorId
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function getVerifiedCanSkipProvePossession($secondFactorId)
    {
        return $this->apiService->read('verified-second-factor/%s/skip-prove-possession', [$secondFactorId]);
    }

    /**
     * @param string $secondFactorId
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function getVetted($secondFactorId)
    {
        return $this->apiService->read('vetted-second-factor/%s', [$secondFactorId]);
    }

    /**
     * @param UnverifiedSecondFactorSearchQuery $query
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function searchUnverified(UnverifiedSecondFactorSearchQuery $query)
    {
        return $this->apiService->read('unverified-second-factors' . $query->toHttpQuery());
    }

    /**
     * @param VerifiedSecondFactorSearchQuery $query
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function searchVerified(VerifiedSecondFactorSearchQuery $query)
    {
        return $this->apiService->read('verified-second-factors' . $query->toHttpQuery());
    }

    /**
     * @param VerifiedSecondFactorOfIdentitySearchQuery $query
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function searchOwnVerified(VerifiedSecondFactorOfIdentitySearchQuery $query)
    {
        return $this->apiService->read('verified-second-factors-of-identity' . $query->toHttpQuery());
    }

    /**
     * @param VettedSecondFactorSearchQuery $query
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function searchVetted(VettedSecondFactorSearchQuery $query)
    {
        return $this->apiService->read('vetted-second-factors' . $query->toHttpQuery());
    }
}
