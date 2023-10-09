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
use DateTime;
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

final class RaSecondFactorExportQuery implements HttpQuery
{
    public const STATUS_UNVERIFIED = 'unverified';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_VETTED = 'vetted';
    public const STATUS_REVOKED = 'revoked';

    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @var string|null
     */
    private ?string $type;

    /**
     * @var string|null The second factor type's ID (eg. Yubikey public ID)
     */
    private ?string $secondFactorId;

    /**
     * @var string|null
     */
    private ?string $email;

    /**
     * @var string|null
     */
    private ?string $institution;

    /**
     * @var string|null One of the STATUS_* constants.
     */
    private ?string $status;

    /**
     * @var string|null
     */
    private ?string $orderBy;

    /**
     * @var string|null
     */
    private ?string $orderDirection;

    /**
     * @var string
     */
    private string $actorId;

    /**
     * @param string $actorId
     */
    public function __construct(string $actorId)
    {
        $this->assertNonEmptyString($actorId, 'actorId');

        $this->actorId = $actorId;
    }

    public function setActorId(string $actorId): self
    {
        $this->actorId = $actorId;

        return $this;
    }

    public function getFileName(): string
    {
        $date = new DateTime();
        $date = $date->format('Y-m-d');

        $fileName = "token_export_{$date}";

        if ($this->type) {
            $fileName .= "-{$this->type}";
        }

        if ($this->status) {
            $fileName .= "-{$this->status}";
        }

        return $fileName;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return null|string
     */
    public function getSecondFactorId(): ?string
    {
        return $this->secondFactorId;
    }

    public function setSecondFactorId(string $secondFactorId): void
    {
        $this->secondFactorId = $secondFactorId;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): void
    {
        $this->institution = $institution;
    }

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        Assert\that($status)->choice(
            [self::STATUS_UNVERIFIED, self::STATUS_VERIFIED, self::STATUS_VETTED, self::STATUS_REVOKED, ''],
            'Invalid second factor status, must be one of the STATUS constants'
        );

        $this->status = $status ?: null;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy(string $orderBy): void
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;
    }

    public function setOrderDirection(string $orderDirection): void
    {
        Assert\that($orderDirection)->choice(
            ['asc', 'desc'],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection;
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

    /**
     * Return the Http Query string as should be used, MUST include the '?' prefix.
     *
     * @return string
     */
    public function toHttpQuery(): string
    {
        return '?' . http_build_query(
            array_filter(
                [
                    'actorId'          => $this->actorId,
                    'name'             => $this->name,
                    'type'             => $this->type,
                    'secondFactorId'   => $this->secondFactorId,
                    'email'            => $this->email,
                    'institution'      => $this->institution,
                    'status'           => $this->status,
                    'orderBy'          => $this->orderBy,
                    'orderDirection'   => $this->orderDirection
                ],
                fn($value): bool => !is_null($value)
            )
        );
    }
}
