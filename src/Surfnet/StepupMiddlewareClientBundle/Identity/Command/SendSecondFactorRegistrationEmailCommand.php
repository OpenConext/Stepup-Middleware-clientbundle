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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Command;

use Surfnet\StepupMiddlewareClientBundle\Command\AbstractCommand;

class SendSecondFactorRegistrationEmailCommand extends AbstractCommand
{
    /**
     * @var string
     */
    public string $identityId;

    /**
     * @var string
     */
    public string $secondFactorId;

    public function serialise(): array
    {
        return [
            'identity_id'      => $this->identityId,
            'second_factor_id' => $this->secondFactorId
        ];
    }
}
