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

namespace Surfnet\StepupMiddlewareClient\Tests\Identity\Service;

use DateTime;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Surfnet\StepupBundle\Service\SecondFactorTypeService;
use Surfnet\StepupMiddlewareClient\Identity\Dto\UnverifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VerifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VerifiedSecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\UnverifiedSecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\SecondFactorService;

class SecondFactorServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testItSearchesUnverifiedSecondFactorsByIdentity()
    {
        $identityId = 'a';

        $secondFactorData = [
            'collection' => ['total_items' => 1, 'page' => 1, 'page_size' => 25],
            'items' => [
                [
                    "id" => "769a6649-b3e8-4dd4-8715-2941f947a016",
                    "type" => "yubikey",
                    "second_factor_identifier" => "ccccccbtbhnh",
                ]
            ],
            "filters" => [],
        ];
        $query = (new UnverifiedSecondFactorSearchQuery())->setIdentityId($identityId);

        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\SecondFactorService')
            ->shouldReceive('searchUnverified')->with($query)->once()->andReturn($secondFactorData)
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->with()->once()->andReturn(0)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $typeService = m::mock(SecondFactorTypeService::class);
        $service = new SecondFactorService($libraryService, $typeService, $validator);
        $secondFactors = $service->searchUnverified($query);

        $expectedSecondFactor = new UnverifiedSecondFactor();
        $expectedSecondFactor->id = $secondFactorData['items'][0]['id'];
        $expectedSecondFactor->type = $secondFactorData['items'][0]['type'];
        $expectedSecondFactor->secondFactorIdentifier = $secondFactorData['items'][0]['second_factor_identifier'];

        $this->assertEquals([$expectedSecondFactor], $secondFactors->getElements());
    }

    public function testItSearchesVerifiedSecondFactorsByIdentity()
    {
        $identityId = 'a';

        $secondFactorData = [
            'collection' => ['total_items' => 1, 'page' => 1, 'page_size' => 25],
            'items' => [
                [
                    "id" => "769a6649-b3e8-4dd4-8715-2941f947a016",
                    "type" => "yubikey",
                    "second_factor_identifier" => "ccccccbtbhnh",
                    'registration_code' => 'abc',
                    'registration_requested_at' => '2017-01-01 10:00:00',
                    "identity_id" => "a",
                    "institution" => "b",
                    "common_name" => "c",
                ]
            ],
            "filters" => [],
        ];
        $query = (new VerifiedSecondFactorSearchQuery())->setIdentityId($identityId);

        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\SecondFactorService')
            ->shouldReceive('searchVerified')->with($query)->once()->andReturn($secondFactorData)
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->with()->once()->andReturn(0)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();
        $typeService = m::mock(SecondFactorTypeService::class);
        $service = new SecondFactorService($libraryService, $typeService, $validator);
        $secondFactors = $service->searchVerified($query);

        $expectedSecondFactor = new VerifiedSecondFactor();
        $expectedSecondFactor->id = $secondFactorData['items'][0]['id'];
        $expectedSecondFactor->type = $secondFactorData['items'][0]['type'];
        $expectedSecondFactor->secondFactorIdentifier = $secondFactorData['items'][0]['second_factor_identifier'];
        $expectedSecondFactor->registrationCode = $secondFactorData['items'][0]['registration_code'];
        $expectedSecondFactor->registrationRequestedAt = new DateTime(
            $secondFactorData['items'][0]['registration_requested_at']
        );
        $expectedSecondFactor->identityId = $secondFactorData['items'][0]['identity_id'];
        $expectedSecondFactor->institution = $secondFactorData['items'][0]['institution'];
        $expectedSecondFactor->commonName = $secondFactorData['items'][0]['common_name'];

        $this->assertEquals([$expectedSecondFactor], $secondFactors->getElements());
    }
}
