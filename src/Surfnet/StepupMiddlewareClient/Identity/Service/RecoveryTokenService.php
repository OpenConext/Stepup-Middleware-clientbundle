<?php

/**
 * Copyright 2022 SURFnet bv
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
use Surfnet\StepupMiddlewareClient\Identity\Dto\RecoveryTokenSearchQuery;
use Surfnet\StepupMiddlewareClient\Service\ApiService;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;

/**
 * Recovery tokens are used in conjunction with self-asserted
 * second factor tokens. A recovery token is registered in
 * SelfService, and can be revoked in RA.
 */
class RecoveryTokenService
{
    /**
     * @var ApiService
     */
    private $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Does the Identity already have a recovery token?
     *
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function hasRecoveryToken(Identity $identity): bool
    {
        $query = new RecoveryTokenSearchQuery();
        $query->setIdentityId((string)$identity);
        $results = $this->apiService->read(sprintf('recovery_tokens%s', $query->toHttpQuery()));
        return !(!$results || empty($results['items']));
    }
}
