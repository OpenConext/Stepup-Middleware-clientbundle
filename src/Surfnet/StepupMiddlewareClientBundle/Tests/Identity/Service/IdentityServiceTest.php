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

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\IdentityService;

class IdentityServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    private $mockIdentity = [
        'id' => '123',
        'name_id' => '456',
        'institution' => 'Foo Inc.',
        'email' => 'a@b.c',
        'common_name' => 'Foo Bar',
        'preferred_locale' => 'en_GB',
    ];

    public function testItGetsAnIdentity()
    {
        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\IdentityService')
            ->shouldReceive('get')->with($this->mockIdentity['id'])->once()->andReturn($this->mockIdentity)
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->once()->andReturn(0)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $service = new IdentityService($libraryService, $validator);
        $identity = $service->get($this->mockIdentity['id']);

        $expectedIdentity = Identity::fromData($this->mockIdentity);

        $this->assertEquals($expectedIdentity, $identity);
    }

    public function testItValidatesTheIdentity()
    {
        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\IdentityService')
            ->shouldReceive('get')->with($this->mockIdentity['id'])->once()->andReturn($this->mockIdentity)
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->with()->once()->andReturn(1)
            ->shouldReceive('getIterator')->with()->once()->andReturn(new \ArrayIterator())
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $service = new IdentityService($libraryService, $validator);
        $this->expectException(InvalidResponseException::class);
        $service->get($this->mockIdentity['id']);
    }
}
