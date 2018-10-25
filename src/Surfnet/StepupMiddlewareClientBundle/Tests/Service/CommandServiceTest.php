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

namespace Surfnet\StepupMiddlewareClientBundle\Tests\Service;

use Mockery as m;
use Surfnet\StepupMiddlewareClientBundle\Command\Command;
use Surfnet\StepupMiddlewareClientBundle\Command\Metadata;
use Surfnet\StepupMiddlewareClientBundle\Service\CommandService;
use Surfnet\StepupMiddlewareClientBundle\Tests\Service\Fixtures\Root\Command\CauseCommand;
use Surfnet\StepupMiddlewareClientBundle\Tests\Service\Fixtures\Root\Command\Name\Spaced\ZigCommand;

class CommandServiceTest extends \PHPUnit_Framework_TestCase
{
    use m\Adapter\Phpunit\MockeryPHPUnitIntegration;
    /**
     * @dataProvider commands
     * @param string $expectedCommandName
     * @param array $expectedPayload
     * @param array $expectedMetadataPayload
     * @param Command $command
     * @param Metadata|null $metadata
     */
    public function testItExecutesCommands(
        $expectedCommandName,
        $expectedPayload,
        $expectedMetadataPayload,
        Command $command,
        Metadata $metadata
    ) {
        $result = m::mock('Surfnet\StepupMiddlewareClient\Service\ExecutionResult')
            ->shouldReceive('isSuccessful')->andReturn(true)
            ->shouldReceive('getUuid')->andReturn('uu-id')
            ->shouldReceive('getProcessedBy')->andReturn('mw-01')
            ->getMock();
        $commandService = m::mock('Surfnet\StepupMiddlewareClient\Service\CommandService')
            ->shouldReceive('execute')->once()->with($expectedCommandName, self::spy($sentUuid), $expectedPayload, $expectedMetadataPayload)->andReturn($result)
            ->getMock();

        $service = new CommandService($commandService, m::mock('Psr\Log\LoggerInterface')->shouldIgnoreMissing());
        $service->execute($command, $metadata);

        $this->assertNotEmpty($command->getUuid(), 'UUID wasn\'t set during command execution');
        $this->assertInternalType('string', $command->getUuid(), 'UUID set is not a string');
        $this->assertEquals($sentUuid, $command->getUuid(), 'UUID set doesn\'t match the UUID sent');
    }

    public function commands()
    {
        return [
            'Non-nested command' => [
                'Root:Cause',
                [1],
                ['actor_id' => 'actorId', 'actor_institution' => 'actorInstitution'],
                new CauseCommand([1]),
                new Metadata('actorId', 'actorInstitution')
            ],
            'Nested command'     => [
                'Root:Name.Spaced.Zig',
                ['all' => 'base'],
                ['actor_id' => 'actorId', 'actor_institution' => 'actorInstitution'],
                new ZigCommand(['all' => 'base']),
                new Metadata('actorId', 'actorInstitution')
            ],
        ];
    }

    public function testItOnlySetsTheUuidIfNotAlreadySet()
    {
        $preSetUuid = 'aaaaaa-bbbb-cccc-dddddddddddd';

        $command = new ZigCommand([]);
        $command->setUuid($preSetUuid);

        $result = m::mock('Surfnet\StepupMiddlewareClient\Service\ExecutionResult')
            ->shouldReceive('isSuccessful')->andReturn(true)
            ->shouldReceive('getUuid')->andReturn($preSetUuid)
            ->shouldReceive('getProcessedBy')->andReturn('mw-01')
            ->getMock();
        $commandService = m::mock('Surfnet\StepupMiddlewareClient\Service\CommandService')
            ->shouldReceive('execute')->once()->with('Root:Name.Spaced.Zig', self::spy($sentUuid), [], ['actor_id' => 'actorId', 'actor_institution' => 'actorInstitution'])->andReturn($result)
            ->getMock();

        $service = new CommandService($commandService, m::mock('Psr\Log\LoggerInterface')->shouldIgnoreMissing());
        $service->execute($command, new Metadata('actorId', 'actorInstitution'));

        $this->assertEquals($preSetUuid, $command->getUuid(), 'UUID was overwritten during command execution');
        $this->assertEquals($preSetUuid, $sentUuid, 'Another UUID than the pre-set UUID was sent to the server');
    }

    private static function spy(&$spiedValue)
    {
        return m::on(function ($value) use (&$spiedValue) {
            $spiedValue = $value;

            return true;
        });
    }
}
