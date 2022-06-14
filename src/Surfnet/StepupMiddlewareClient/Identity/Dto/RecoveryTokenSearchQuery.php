<?php

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

namespace Surfnet\StepupMiddlewareClient\Identity\Dto;

use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

final class RecoveryTokenSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $identityId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $pageNumber;

    public function setIdentityId(string $identityId): void
    {
        $this->identityId = $identityId;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setPageNumber(int $pageNumber): void
    {
        $this->pageNumber = $pageNumber;
    }

    public function toHttpQuery(): string
    {
        $fields = [];

        if ($this->identityId) {
            $fields['identityId'] = $this->identityId;
        }

        if ($this->type) {
            $fields['type'] = $this->type;
        }

        if ($this->pageNumber) {
            $fields['p'] = $this->pageNumber;
        }

        return '?' . http_build_query($fields);
    }
}
