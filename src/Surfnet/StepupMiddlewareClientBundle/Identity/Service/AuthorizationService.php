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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Service;

use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClient\Identity\Service\AuthorizationService as LibraryAuthorizationService;

class AuthorizationService
{
    public function __construct(private readonly LibraryAuthorizationService $authorizationService)
    {
    }

    public function assertRegistrationOfSelfAssertedTokensIsAllowed(Identity $identity): bool
    {
        return $this->authorizationService->assertRegistrationOfSelfAssertedTokensIsAllowed($identity);
    }

    public function assertSelfVettingOfSelfAssertedTokensIsAllowed(Identity $identity): bool
    {
        return $this->authorizationService->assertSelfVettingOfSelfAssertedTokensIsAllowed($identity);
    }

    public function assertRegistrationOfRecoveryTokensIsAllowed(Identity $identity): bool
    {
        return $this->authorizationService->assertRegistrationOfRecoveryTokensAreAllowed($identity);
    }
}
