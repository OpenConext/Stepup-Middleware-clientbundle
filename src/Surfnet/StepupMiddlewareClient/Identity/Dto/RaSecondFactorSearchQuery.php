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

final class RaSecondFactorSearchQuery implements HttpQuery
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
     * @var string|null One of the STATUS_* constants.
     */
    private ?string $status;

    /**
     * @var string|null
     */
    private ?string $institution;

    /**
     * @var string|null
     */
    private ?string $orderBy;

    /**
     * @var string|null
     */
    private ?string $orderDirection;

    /**
     * @var int
     */
    private int $pageNumber;

    /**
     * @param int $pageNumber
     * @param string $actorId
     */
    public function __construct(int $pageNumber, private string $actorId)
    {
        Assert\that($pageNumber)
            ->integer('Page number must be an integer')
            ->min(1, 'Page number must be greater than or equal to 1');
        $this->pageNumber = $pageNumber;
    }

    public function setActorId(string $actorId): self
    {
        $this->assertNonEmptyString($actorId, 'actorId');

        $this->actorId = $actorId;

        return $this;
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
        $this->assertNonEmptyString($email, 'email');
        $this->email = $email;
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

        $this->orderDirection = $orderDirection ?: null;
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
                    'status'           => $this->status,
                    'institution'      => $this->institution,
                    'orderBy'          => $this->orderBy,
                    'orderDirection'   => $this->orderDirection,
                    'p'                => $this->pageNumber,
                ],
                fn($value): bool => !is_null($value)
            )
        );
    }
}
