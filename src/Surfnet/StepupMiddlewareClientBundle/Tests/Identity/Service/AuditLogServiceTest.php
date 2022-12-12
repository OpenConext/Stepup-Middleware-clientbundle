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
use Surfnet\StepupMiddlewareClient\Identity\Dto\SecondFactorAuditLogSearchQuery;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\AuditLog;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\AuditLogEntry;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\AuditLogService;

class AuditLogServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    private $mockAuditLog = <<<'JSON'
{
  "collection":{
    "total_items":7,
    "page":1,
    "page_size":25
  },
  "items":[
    {
      "actor_id":"5613875b-410e-407c-91ce-35bf0b5a8d89",
      "actor_institution":"Ibuildings bv",
      "ra_institution":"Ibuildings bv",
      "actor_common_name":"Foo Bar",
      "identity_id":"5613875b-410e-407c-91ce-35bf0b5a8d89",
      "identity_institution":"Ibuildings bv",
      "second_factor_id":"1d645ab2-e523-4462-b85e-44f194f80bd6",
      "second_factor_type":"yubikey",
      "second_factor_identifier":"ccccvfeghijk",
      "action":"email_verified",
      "recorded_on":"2015-03-31T12:07:28+02:00"
    },
    {
      "actor_id":"5613875b-410e-407c-91ce-35bf0b5a8d89",
      "actor_institution":"Ibuildings bv",
      "ra_institution":"Ibuildings bv",
      "actor_common_name":"Foo Bar",
      "identity_id":"5613875b-410e-407c-91ce-35bf0b5a8d89",
      "identity_institution":"Ibuildings bv",
      "second_factor_id":"1d645ab2-e523-4462-b85e-44f194f80bd6",
      "second_factor_type":"yubikey",
      "second_factor_identifier":"ccccvfeghijk",
      "action":"possession_proven",
      "recorded_on":"2015-03-31T12:07:12+02:00"
    }
  ],
  "filters":[]
}
JSON;


    public function testItGetsAnIdentity()
    {
        $query = new SecondFactorAuditLogSearchQuery('Ibuildings bv', '5613875b-410e-407c-91ce-35bf0b5a8d89', 1);

        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\AuditLogService')
            ->shouldReceive('searchSecondFactorAuditLog')->with($query)->once()->andReturn(json_decode($this->mockAuditLog, true))
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->once()->andReturn(0)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $service = new AuditLogService($libraryService, $validator);
        $actualAuditLog = $service->searchSecondFactorAuditLog($query);

        $expectedAuditLog = AuditLog::fromData(json_decode($this->mockAuditLog, true));

        $this->assertEquals($expectedAuditLog, $actualAuditLog);

        /** @var AuditLogEntry $entry */
        $entry = $actualAuditLog->getElements()[0];
        $this->assertEquals('5613875b-410e-407c-91ce-35bf0b5a8d89', $entry->actorId);
        $this->assertEquals('Ibuildings bv', $entry->actorInstitution);
        $this->assertEquals('Foo Bar', $entry->actorCommonName);
        $this->assertEquals('5613875b-410e-407c-91ce-35bf0b5a8d89', $entry->identityId);
        $this->assertEquals('Ibuildings bv', $entry->identityInstitution);
        $this->assertEquals('1d645ab2-e523-4462-b85e-44f194f80bd6', $entry->secondFactorId);
        $this->assertEquals('yubikey', $entry->secondFactorType);
        $this->assertEquals('ccccvfeghijk', $entry->secondFactorIdentifier);
        $this->assertEquals('email_verified', $entry->action);
        $this->assertEquals(new DateTime('2015-03-31 12:07:28 +02:00'), $entry->recordedOn);
    }
}
