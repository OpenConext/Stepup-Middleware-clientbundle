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

use Surfnet\StepupMiddlewareClient\Identity\Dto\RaCandidateSearchQuery;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

class RaCandidateService
{
    /**
     * @var ApiService
     */
    private $apiClient;

    /**
     * @param ApiService $apiClient
     */
    public function __construct(ApiService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param RaCandidateSearchQuery $query
     * @return array|null
     */
    public function search(RaCandidateSearchQuery $query)
    {
        return $this->apiClient->read('ra-candidate' . $query->toHttpQuery());
    }

    /**
     * @param string $identityId
     * @param string $identityId
     * @return array|null
     */
    public function get($identityId, $institution)
    {
        return $this->apiClient->read('ra-candidate/%s/%s', [$identityId, $institution]);
    }
}
