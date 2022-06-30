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

namespace Surfnet\StepupMiddlewareClientBundle\Tests\Identity\Service;

use Mockery;
use PHPUnit\Framework\TestCase;
use Surfnet\StepupMiddlewareClient\Identity\Service\AuthorizationService as ApiService;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\AuthorizationService;

class AuthorizationServiceTest extends TestCase
{
    private $service;

    private $apiService;

    public function setUp(): void
    {
        $this->apiService = Mockery::mock(ApiService::class);
        $this->service = new AuthorizationService($this->apiService);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_self_asserted_tokens_authorization_can_be_performed_positive_outcome()
    {
        $identity = new Identity();
        $identity->id = 'ff17c086-ebae-11ec-8ea0-0242ac120002';
        $identity->commonName = 'Evangelos Odysseas Papathanassiou';
        $this->apiService
            ->shouldReceive('assertRegistrationOfSelfAssertedTokensIsAllowed')
            ->with($identity)
            ->andReturnTrue();
        $this->assertTrue($this->service->assertRegistrationOfSelfAssertedTokensIsAllowed($identity));
    }

    public function test_self_asserted_tokens_authorization_can_be_performed_negative_outcome()
    {
        $identity = new Identity();
        $identity->id = 'ff17c086-ebae-11ec-8ea0-0242ac120002';
        $identity->commonName = 'Evangelos Odysseas Papathanassiou';
        $this->apiService
            ->shouldReceive('assertRegistrationOfRecoveryTokensAreAllowed')
            ->with($identity)
            ->andReturnFalse();
        $this->assertFalse($this->service->assertRegistrationOfRecoveryTokensIsAllowed($identity));
    }

    public function test_recovery_token_authorization_can_be_performed_positive_outcome()
    {
        $identity = new Identity();
        $identity->id = 'ff17c086-ebae-11ec-8ea0-0242ac120002';
        $identity->commonName = 'Evangelos Odysseas Papathanassiou';
        $this->apiService
            ->shouldReceive('assertRegistrationOfRecoveryTokensAreAllowed')
            ->with($identity)
            ->andReturnTrue();
        $this->assertTrue($this->service->assertRegistrationOfRecoveryTokensIsAllowed($identity));
    }

    public function test_recovery_token_authorization_can_be_performed_negative_outcome()
    {
        $identity = new Identity();
        $identity->id = 'ff17c086-ebae-11ec-8ea0-0242ac120002';
        $identity->commonName = 'Evangelos Odysseas Papathanassiou';
        $this->apiService
            ->shouldReceive('assertRegistrationOfSelfAssertedTokensIsAllowed')
            ->with($identity)
            ->andReturnFalse();
        $this->assertFalse($this->service->assertRegistrationOfSelfAssertedTokensIsAllowed($identity));
    }
}
