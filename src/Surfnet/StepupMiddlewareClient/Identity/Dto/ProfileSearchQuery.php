<?php

declare(strict_types = 1);

/**
 * Copyright 2019 SURFnet B.V.
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

class ProfileSearchQuery implements HttpQuery
{
    public function __construct(private string $identityId, private string $actorId)
    {
    }

    public function getIdentityId(): string
    {
        return $this->identityId;
    }

    public function setActorId(string $actorId): self
    {
        $this->assertNonEmptyString($actorId, 'institution');

        $this->actorId = $actorId;

        return $this;
    }

    public function setIdentityId(string $identityId): self
    {
        $this->assertNonEmptyString($identityId, 'institutionId');

        $this->identityId = $identityId;

        return $this;
    }

    private function assertNonEmptyString(string $value, string $name): void
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
        if ($this->actorId !== '' && $this->actorId !== '0') {
            $fields = ['actorId' => $this->actorId];
        }

        return '?' . http_build_query($fields);
    }
}
