<?php

/**
 * Copyright 2021 SURF bv
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

class SelfVetSecondFactorCommand extends AbstractCommand
{
    /**
     * @var string
     */
    public $authorityId;

    /**
     * @var string
     */
    public $identityId;

    /**
     * @var string
     */
    public $secondFactorId;

    /**
     * @var string
     */
    public $registrationCode;

    /**
     * @var string
     */
    public $secondFactorType;

    /**
     * @var string
     */
    public $secondFactorIdentifier;

    /**
     * @var string
     */
    public $authoringSecondFactorLoa;

    /**
     * @var string
     */
    public $authoringSecondFactorId;

    public function serialise()
    {
        return [
            'authority_id' => $this->authorityId,
            'identity_id' => $this->identityId,
            'second_factor_id' => $this->secondFactorId,
            'second_factor_type' => $this->secondFactorType,
            'registration_code' => $this->registrationCode,
            'second_factor_identifier' => $this->secondFactorIdentifier,
            'authoring_second_factor_loa' => $this->authoringSecondFactorLoa,
            'authoring_second_factor_id' => $this->authoringSecondFactorId,
        ];
    }
}
