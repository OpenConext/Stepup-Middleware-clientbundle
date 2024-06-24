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

namespace Surfnet\StepupMiddlewareClient\Identity\Service;

use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;
use Surfnet\StepupMiddlewareClient\Identity\Dto\IdentitySearchQuery;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

/**
 * Provides remote read access to the Middleware's identities.
 */
class IdentityService
{
    public function __construct(private readonly ApiService $apiService)
    {
    }

    /**
     * @param string $id
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function get(string $id): ?array
    {
        return $this->apiService->read('identity/%s', [$id]);
    }

    public function search(IdentitySearchQuery $searchQuery): ?array
    {
        return $this->apiService->read('identity', [], $searchQuery);
    }

    /**
     * @param string $identityId
     * @return array|null
     */
    public function getRegistrationAuthorityCredentials(string $identityId): ?array
    {
        return $this->apiService->read('registration-authority/%s', [$identityId]);
    }
}
