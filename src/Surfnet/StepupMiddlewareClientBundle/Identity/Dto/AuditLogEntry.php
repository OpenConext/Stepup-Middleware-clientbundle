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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Dto;

use DateTime;
use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;

final class AuditLogEntry implements Dto
{
    /**
     * @var string|null
     */
    public $actorId;

    /**
     * @var string|null
     */
    public $actorInstitution;

    /**
     * @var string|null
     */
    public $raInstitution;

    /**
     * @var string
     */
    public $actorCommonName;

    /**
     * @var string
     */
    public $identityId;

    /**
     * @var string
     */
    public $identityInstitution;

    /**
     * @var string|null
     */
    public $secondFactorId;

    /**
     * @var string|null
     */
    public $secondFactorType;

    /**
     * @var string
     */
    public $secondFactorIdentifier;

    /**
     * @var string
     */
    public $recoveryTokenIdentifier;

    /**
     * @var string
     */
    public $recoveryTokenType;

    /**
     * @var string
     */
    public $action;

    /**
     * @var DateTime
     */
    public $recordedOn;

    /**
     * @param array $data
     * @return static
     */
    public static function fromData(array $data)
    {
        $entry                         = new self();
        $entry->actorId                = $data['actor_id'];
        $entry->actorInstitution       = $data['actor_institution'];
        $entry->actorCommonName        = $data['actor_common_name'];
        $entry->raInstitution          = $data['ra_institution'];
        $entry->identityId             = $data['identity_id'];
        $entry->identityInstitution    = $data['identity_institution'];
        $entry->secondFactorId         = $data['second_factor_id'];
        $entry->secondFactorType       = $data['second_factor_type'];
        $entry->secondFactorIdentifier = $data['second_factor_identifier'];
        $entry->recoveryTokenIdentifier = $data['recovery_token_identifier'];
        $entry->recoveryTokenType = $data['recovery_token_type'];
        $entry->action                 = $data['action'];
        $entry->recordedOn             = new DateTime($data['recorded_on']);

        return $entry;
    }

    private function __construct()
    {
    }
}
