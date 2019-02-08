<?php

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

final class RaListingSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $actorId;

    /**
     * @var string
     */
    private $actorInstitution;

    /**
     * @var string|null
     */
    private $institution;

    /**
     * @var string|null
     */
    private $identityId;

    /**
     * @var int
     */
    private $pageNumber;

    /**
     * @var string|null
     */
    private $orderBy = 'commonName';

    /**
     * @var string|null
     */
    private $orderDirection = 'asc';

    /**
     * @param string $actorId
     * @param string string $actorInstitution
     * @param int $pageNumber
     */
    public function __construct($actorId, $actorInstitution, $pageNumber)
    {
        $this->assertNonEmptyString($actorId, 'actorId');
        $this->assertNonEmptyString($actorInstitution, 'actorInstitution');
        Assert\that($pageNumber)
            ->integer('Page number must be an integer')
            ->min(0, 'Page number must be greater than or equal to 1');

        $this->actorId = $actorId;
        $this->actorInstitution = $actorInstitution;
        $this->pageNumber  = $pageNumber;
    }

    /**
     * @param string $institution
     * @return $this
     */
    public function setInstitution($institution)
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;

        return $this;
    }

    /**
     * @param string $identityId
     * @return RaListingSearchQuery
     */
    public function setIdentityId($identityId)
    {
        $this->assertNonEmptyString($identityId, 'identityId');

        $this->identityId = $identityId;

        return $this;
    }

    /**
     * @param string $orderBy
     * @return RaListingSearchQuery
     */
    public function setOrderBy($orderBy)
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @param string|null $orderDirection
     * @return RaListingSearchQuery
     */
    public function setOrderDirection($orderDirection)
    {
        Assert\that($orderDirection)->choice(
            ['asc', 'desc', '', null],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection ?: null;

        return $this;
    }

    private function assertNonEmptyString($value, $parameterName)
    {
        $message = sprintf(
            '"%s" must be a non-empty string, "%s" given',
            $parameterName,
            (is_object($value) ? get_class($value) : gettype($value))
        );

        Assert\that($value)->string($message)->notEmpty($message);
    }

    public function toHttpQuery()
    {
        return '?' . http_build_query(
            array_filter(
                [
                    'actorId'          => $this->actorId,
                    'actorInstitution' => $this->actorInstitution,
                    'institution'      => $this->institution,
                    'identityId '      => $this->identityId,
                    'orderBy'          => $this->orderBy,
                    'orderDirection'   => $this->orderDirection,
                    'p'                => $this->pageNumber,
                ],
                function ($value) {
                    return !is_null($value);
                }
            )
        );
    }
}
