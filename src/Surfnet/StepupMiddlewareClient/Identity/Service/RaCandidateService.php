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

use Surfnet\StepupMiddlewareClient\Identity\Dto\RaCandidateSearchQuery;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

class RaCandidateService
{
    public function __construct(private readonly ApiService $apiClient)
    {
    }

    /**
     * @return array|null
     */
    public function search(RaCandidateSearchQuery $query): ?array
    {
        return $this->apiClient->read('ra-candidate' . $query->toHttpQuery());
    }

    /**
     * @return array|null
     */
    public function get(string $identityId, string $actorId): ?array
    {
        return $this->apiClient->read('ra-candidate/%s?actorId=%s', [$identityId, $actorId]);
    }
}
