<?php

declare(strict_types = 1);

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

namespace Surfnet\StepupMiddlewareClientBundle\Command;

use Surfnet\StepupMiddlewareClientBundle\Exception\DomainException;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidArgumentException;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren) since well, we have more than 15 commands
 */
abstract class AbstractCommand implements Command
{
    private ?string $commandUuid = null;

    public function getUuid()
    {
        return $this->commandUuid;
    }

    public function setUuid($uuid): void
    {
        if (!is_string($uuid)) {
            InvalidArgumentException::invalidType('string', 'uuid', $uuid);
        }

        if ($this->commandUuid) {
            throw new DomainException('Command UUID may not be overwritten');
        }

        $this->commandUuid = $uuid;
    }
}
