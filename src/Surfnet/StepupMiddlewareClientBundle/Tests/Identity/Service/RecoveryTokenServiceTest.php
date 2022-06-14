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
use Surfnet\StepupMiddlewareClient\Identity\Service\RecoveryTokenService as ApiService;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\RecoveryTokenService;

class RecoveryTokenServiceTest extends TestCase
{
    private $service;

    private $apiService;

    private $vangelis;

    private $eno;

    protected function setUp(): void
    {
        $this->vangelis = new Identity();
        $this->vangelis->id = 'ff17c086-ebae-11ec-8ea0-0242ac120002';
        $this->vangelis->commonName = 'Evangelos Odysseas Papathanassiou';

        $this->eno = new Identity();
        $this->eno->id = '1b0d8788-ebb1-11ec-8ea0-0242ac120002';
        $this->eno->commonName = 'Brian Peter George St John le Baptiste de la Salle Eno';

        $this->apiService = Mockery::mock(ApiService::class);
        $this->apiService
            ->shouldReceive('hasRecoveryToken')
            ->with($this->vangelis)
            ->andReturnTrue();

        $this->apiService
            ->shouldReceive('hasRecoveryToken')
            ->with($this->eno)
            ->andReturnFalse();

        $this->service = new RecoveryTokenService($this->apiService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_assert_recovery_token()
    {
        $this->assertTrue($this->service->hasRecoveryToken($this->vangelis));
        $this->assertFalse($this->service->hasRecoveryToken($this->eno));
    }

}
