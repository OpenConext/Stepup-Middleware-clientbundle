<?php

declare(strict_types = 1);

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
use Surfnet\StepupMiddlewareClient\Service\ApiService;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use function array_key_exists;

/**
 * Consults the authorization endpoints of the Middleware API
 *
 * Some authorizations entail more complex business logic, in those
 * cases authorizations are delegated to the Middleware.
 *
 * For example, testing when an Identity is allowed to register
 * self-asserted tokens is one of the authz checks available on that API.
 */
class AuthorizationService
{
    public function __construct(private readonly ApiService $apiService)
    {
    }

    /**
     * Is the Identity allowed to register a token using self-asserted registration?
     *
     * Based on conditions:
     * - Is the Institution of the Identity configured with allowance of this feature?
     * - Is Identity authorized to use the self-asserted token registration feature?
     *
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function assertRegistrationOfSelfAssertedTokensIsAllowed(Identity $identity): bool
    {
        $response = $this->apiService->read(
            sprintf('/authorization/may-register-self-asserted-tokens/%s', $identity->id)
        );
        return $response && array_key_exists('code', $response) && $response['code'] === 200;
    }

    /**
     * Is an identity allowed to self vet using a self-asserted token?
     *
     * One is allowed to do so when:
     *  - SAT is allowed for the institution of the identity
     *  - All the tokens of the identity are vetted using the SAT vetting type
     *
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function assertSelfVettingOfSelfAssertedTokensIsAllowed(Identity $identity): bool
    {
        $response = $this->apiService->read(
            sprintf('/authorization/may-self-vet-using-self-asserted-token/%s', $identity->id)
        );
        return $response && array_key_exists('code', $response) && $response['code'] === 200;
    }

    /**
     * Is the Identity allowed to register a Recovery Token?
     *
     * Based on:
     * - Does the Identity have a self-asserted token?
     *
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function assertRegistrationOfRecoveryTokensAreAllowed(Identity $identity): bool
    {
        $response = $this->apiService->read(
            sprintf('/authorization/may-register-recovery-tokens/%s', $identity->id)
        );
        return $response && array_key_exists('code', $response) && $response['code'] === 200;
    }
}
