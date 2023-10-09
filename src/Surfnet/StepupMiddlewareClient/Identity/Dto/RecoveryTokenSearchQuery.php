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

namespace Surfnet\StepupMiddlewareClient\Identity\Dto;

use Assert\Assert;
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

final class RecoveryTokenSearchQuery implements HttpQuery
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_REVOKED = 'revoked';
    public const STATUS_FORGOTTEN = 'forgotten';

    public function __construct(private int $pageNumber, private string $actorId)
    {
    }

    private ?string $identityId = null;

    private ?string $type = null;

    private ?string $name = null;

    private ?string $email = null;

    private ?string $institution = null;

    /**
     * @var string|null One of the STATUS_* constants.
     */
    private ?string $status = null;

    private ?string $orderBy = null;

    /**
     * @var string|null
     */
    private ?string $orderDirection;

    public function setActorId(string $actorId): self
    {
        $this->actorId = $actorId;
        return $this;
    }

    public function setIdentityId(string $identityId): void
    {
        $this->identityId = $identityId;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setInstitution(string $institution): void
    {
        $this->institution = $institution;
    }

    public function setPageNumber(int $pageNumber): void
    {
        $this->pageNumber = $pageNumber;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        Assert::that(
            [self::STATUS_ACTIVE, self::STATUS_REVOKED, self::STATUS_FORGOTTEN, ''],
            'Invalid recovery token status, must be one of the STATUS constants'
        );

        $this->status = $status ?: null;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    public function setOrderDirection(string $orderDirection): void
    {
        $this->orderDirection = $orderDirection ?: null;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function toHttpQuery(): string
    {
        $fields = [];

        if ($this->actorId !== '' && $this->actorId !== '0') {
            $fields['actorId'] = $this->actorId;
        }

        if ($this->identityId) {
            $fields['identityId'] = $this->identityId;
        }

        if ($this->type) {
            $fields['type'] = $this->type;
        }

        if ($this->email) {
            $fields['email'] = $this->email;
        }

        if ($this->name) {
            $fields['name'] = $this->name;
        }

        if ($this->institution) {
            $fields['institution'] = $this->institution;
        }

        if ($this->status) {
            $fields['status'] = $this->status;
        }

        if ($this->orderBy) {
            $fields['orderBy'] = $this->orderBy;
        }

        if ($this->orderDirection) {
            $fields['orderDirection'] = $this->orderDirection;
        }

        if ($this->pageNumber !== 0) {
            $fields['p'] = $this->pageNumber;
        }

        return '?' . http_build_query($fields);
    }
}
