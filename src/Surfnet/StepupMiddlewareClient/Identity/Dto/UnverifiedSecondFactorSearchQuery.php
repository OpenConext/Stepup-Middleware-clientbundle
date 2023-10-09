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

class UnverifiedSecondFactorSearchQuery implements HttpQuery
{
    private ?string $identityId = null;

    /**
     * @var string|null
     */
    private ?string $verificationNonce = null;

    public function setIdentityId(string $identityId): static
    {
        $this->assertNonEmptyString($identityId, 'identityId');

        $this->identityId = $identityId;

        return $this;
    }

    public function setVerificationNonce(string $verificationNonce): static
    {
        $this->assertNonEmptyString($verificationNonce, 'verificationNonce');

        $this->verificationNonce = $verificationNonce;

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

        if ($this->identityId !== '' && $this->identityId !== '0') {
            $fields['identityId'] = $this->identityId;
        }

        if ($this->verificationNonce) {
            $fields['verificationNonce'] = $this->verificationNonce;
        }

        return '?' . http_build_query($fields);
    }
}
