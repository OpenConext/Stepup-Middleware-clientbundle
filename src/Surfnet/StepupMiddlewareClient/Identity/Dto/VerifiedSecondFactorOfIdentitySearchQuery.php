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

class VerifiedSecondFactorOfIdentitySearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private string $identityId;

    public function setIdentityId(string $identityId): self
    {
        $message = sprintf(
            '"%s" must be a non-empty string, "%s" given',
            'identityId',
            (get_debug_type($identityId))
        );
        Assert\that($identityId)->notEmpty($message);

        $this->identityId = $identityId;

        return $this;
    }

    public function toHttpQuery(): string
    {
        $fields = [];
        $fields['identityId'] = $this->identityId;

        return '?' . http_build_query($fields);
    }
}
