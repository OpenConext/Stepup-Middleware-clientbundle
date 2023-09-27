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

class RaCandidateSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private readonly string $actorId;

    /**
     * @var string
     */
    private string $institution;

    /**
     * @var string
     */
    private string $commonName;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $raInstitution;

    private readonly int $pageNumber;

    /**
     * @var string
     */
    private string $orderBy;

    /**
     * @var string
     */
    private string $orderDirection;

    /**
     * @var string[]
     */
    private array $secondFactorTypes = [];

    public function __construct(string $actorId, int $pageNumber)
    {
        $this->assertNonEmptyString($actorId, 'actorId');
        Assert\that($pageNumber)
            ->integer('Page number must be an integer')
            ->min(0, 'Page number must be greater than or equal to 1');

        $this->actorId = $actorId;
        $this->pageNumber  = $pageNumber;
    }

    public function setCommonName(string $commonName): self
    {
        $this->assertNonEmptyString($commonName, 'commonName');

        $this->commonName = $commonName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->assertNonEmptyString($email, 'email');

        $this->email = $email;

        return $this;
    }

    public function setInstitution(string $institution): self
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;

        return $this;
    }

    public function setRaInstitution(string $raInstitution): self
    {
        $this->raInstitution = $raInstitution;
        return $this;
    }

    public function setSecondFactorTypes(array $secondFactorTypes): void
    {
        foreach ($secondFactorTypes as $value) {
            $this->assertNonEmptyString(
                $value,
                'secondFactorTypes',
                'Elements of "%s" must be non-empty strings, element of type "%s" given'
            );
        }

        $this->secondFactorTypes = $secondFactorTypes;
    }

    public function setOrderBy(string $orderBy): self
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;

        return $this;
    }

    public function setOrderDirection(string $orderDirection): self
    {
        $this->assertNonEmptyString($orderDirection, 'orderDirection');
        Assert\that($orderDirection)->choice(
            ['asc', 'desc', '', null],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection;

        return $this;
    }

    public function toHttpQuery(): string
    {
        return '?' . http_build_query(
            array_filter(
                [
                    'actorId'           => $this->actorId,
                    'institution'       => $this->institution,
                    'commonName'        => $this->commonName,
                    'email'             => $this->email,
                    'raInstitution'     => $this->raInstitution,
                    'secondFactorTypes' => $this->secondFactorTypes,
                    'orderBy'           => $this->orderBy,
                    'orderDirection'    => $this->orderDirection,
                    'p'                 => $this->pageNumber,
                ],
                fn($value): bool => !is_null($value)
            )
        );
    }

    private function assertNonEmptyString(mixed $value, string $parameterName, string $message = null): void
    {
        $message = sprintf(
            $message ?: '"%s" must be a non-empty string, "%s" given',
            $parameterName,
            (get_debug_type($value))
        );

        Assert\that($value)->string($message)->notEmpty($message);
    }
}
