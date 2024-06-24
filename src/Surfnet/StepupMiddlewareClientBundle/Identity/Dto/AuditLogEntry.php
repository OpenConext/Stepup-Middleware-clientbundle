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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Dto;

use DateTime;
use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;

final class AuditLogEntry implements Dto
{
    /**
     * @var string|null
     */
    public ?string $actorId;

    /**
     * @var string|null
     */
    public ?string $actorInstitution;

    /**
     * @var string|null
     */
    public ?string $raInstitution;

    /**
     * @var string
     */
    public string $actorCommonName;

    /**
     * @var string
     */
    public string $identityId;

    /**
     * @var string
     */
    public string $identityInstitution;

    /**
     * @var string|null
     */
    public ?string $secondFactorId;

    /**
     * @var string|null
     */
    public ?string $secondFactorType;

    /**
     * @var string
     */
    public ?string $secondFactorIdentifier;

    /**
     * @var string
     */
    public ?string $recoveryTokenIdentifier;

    /**
     * @var string
     */
    public ?string $recoveryTokenType;

    /**
     * @var string
     */
    public string $action;

    /**
     * @var DateTime
     */
    public DateTime $recordedOn;

    public static function fromData(array $data): self
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
