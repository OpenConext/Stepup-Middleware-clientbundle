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

namespace Surfnet\StepupMiddlewareClient\Identity\Dto;

use Assert;
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

class VerifiedSecondFactorSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private string $identityId;

    /**
     * @var string
     */
    private string $secondFactorId;

    /**
     * @var string
     */
    private string $registrationCode;

    /**
     * @var string
     */
    private string $institution;
    /**
     * @var string
     */
    private string $actorId;

    public function setIdentityId(string $identityId): static
    {
        $this->assertNonEmptyString($identityId, 'identityId');

        $this->identityId = $identityId;

        return $this;
    }

    public function setSecondFactorId(string $secondFactorId): static
    {
        $this->assertNonEmptyString($secondFactorId, 'secondFactorId');

        $this->secondFactorId = $secondFactorId;

        return $this;
    }

    public function setRegistrationCode(string $registrationCode): static
    {
        $this->assertNonEmptyString($registrationCode, 'registrationCode');

        $this->registrationCode = $registrationCode;

        return $this;
    }

    public function setInstitution(string $institution): static
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;

        return $this;
    }

    public function setActorId(string $actorId): static
    {
        $this->assertNonEmptyString($actorId, 'actorId');

        $this->actorId = $actorId;

        return $this;
    }

    private function assertNonEmptyString(string $value, string $name): void
    {
        $message = sprintf(
            '"%s" must be a non-empty string, "%s" given',
            $name,
            (get_debug_type($value))
        );

        Assert\that($value)->notEmpty($message);
    }

    public function toHttpQuery(): string
    {
        $fields = [];

        $fields['institution'] = $this->institution;

        if ($this->identityId !== '' && $this->identityId !== '0') {
            $fields['identityId'] = $this->identityId;
        }

        if ($this->secondFactorId !== '' && $this->secondFactorId !== '0') {
            $fields['secondFactorId'] = $this->secondFactorId;
        }

        if ($this->registrationCode !== '' && $this->registrationCode !== '0') {
            $fields['registrationCode'] = $this->registrationCode;
        }

        if ($this->actorId !== '' && $this->actorId !== '0') {
            $fields['actorId'] = $this->actorId;
        }

        return '?' . http_build_query($fields);
    }
}
