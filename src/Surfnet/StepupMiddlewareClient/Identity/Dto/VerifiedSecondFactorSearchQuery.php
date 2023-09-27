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
    private $identityId;

    /**
     * @var string
     */
    private $secondFactorId;

    /**
     * @var string
     */
    private $registrationCode;

    /**
     * @var string
     */
    private $institution;
    /**
     * @var string
     */
    private $actorId;

    /**
     * @param string $identityId
     * @return self
     */
    public function setIdentityId($identityId): static
    {
        $this->assertNonEmptyString($identityId, 'identityId');

        $this->identityId = $identityId;

        return $this;
    }

    /**
     * @param string $secondFactorId
     * @return self
     */
    public function setSecondFactorId($secondFactorId): static
    {
        $this->assertNonEmptyString($secondFactorId, 'secondFactorId');

        $this->secondFactorId = $secondFactorId;

        return $this;
    }

    /**
     * @param string $registrationCode
     * @return self
     */
    public function setRegistrationCode($registrationCode): static
    {
        $this->assertNonEmptyString($registrationCode, 'registrationCode');

        $this->registrationCode = $registrationCode;

        return $this;
    }

    /**
     * @param string $institution
     * @return VerifiedSecondFactorSearchQuery
     */
    public function setInstitution($institution): static
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;

        return $this;
    }

    /**
     * @param string $actorId
     * @return VerifiedSecondFactorSearchQuery
     */
    public function setActorId($actorId): static
    {
        $this->assertNonEmptyString($actorId, 'actorId');

        $this->actorId = $actorId;

        return $this;
    }

    private function assertNonEmptyString($value, string $name): void
    {
        $message = sprintf(
            '"%s" must be a non-empty string, "%s" given',
            $name,
            (get_debug_type($value))
        );

        Assert\that($value)->string($message)->notEmpty($message);
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
