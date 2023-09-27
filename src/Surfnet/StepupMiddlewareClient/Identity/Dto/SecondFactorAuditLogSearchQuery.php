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

final class SecondFactorAuditLogSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $institution;

    /**
     * @var string
     */
    private $identityId;

    /**
     * @var string|null
     */
    private string $orderBy = 'recordedOn';

    /**
     * @var string|null
     */
    private $orderDirection = 'desc';

    /**
     * @var int
     */
    private $pageNumber;

    /**
     * @param string $institution
     * @param string $identityId
     * @param int    $pageNumber
     */
    public function __construct($institution, $identityId, $pageNumber)
    {
        $this->assertNonEmptyString($institution, 'institution');
        $this->assertNonEmptyString($identityId, 'identityId');
        Assert\that($pageNumber)
            ->integer('Page number must be an integer')
            ->min(0, 'Page number must be greater than or equal to 1');

        $this->institution = $institution;
        $this->identityId = $identityId;
        $this->pageNumber = $pageNumber;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;
    }

    /**
     * @param string|null $orderDirection
     */
    public function setOrderDirection($orderDirection): void
    {
        Assert\that($orderDirection)->choice(
            ['asc', 'desc', '', null],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection ?: null;
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

    /**
     * Return the Http Query string as should be used, MUST include the '?' prefix.
     *
     * @return string
     */
    public function toHttpQuery(): string
    {
        return '?' . http_build_query(
            [
                'institution'    => $this->institution,
                'identityId'     => $this->identityId,
                'orderBy'        => $this->orderBy,
                'orderDirection' => $this->orderDirection,
                'p'              => $this->pageNumber,
            ]
        );
    }
}
