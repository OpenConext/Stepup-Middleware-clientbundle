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

class RaCandidateSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $actorInstitution;

    /**
     * @var string
     */
    private $institution;

    /**
     * @var string
     */
    private $commonName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $pageNumber = 1;

    /**
     * @var string
     */
    private $orderBy;

    /**
     * @var string
     */
    private $orderDirection;

    /**
     * @var string[]
     */
    private $secondFactorTypes = [];

    /**
     * @param string $institution
     * @param int    $pageNumber
     */
    public function __construct($actorInstitution, $pageNumber)
    {
        $this->assertNonEmptyString($actorInstitution, 'actorInstitution');
        Assert\that($pageNumber)
            ->integer('Page number must be an integer')
            ->min(0, 'Page number must be greater than or equal to 1');

        $this->actorInstitution = $actorInstitution;
        $this->pageNumber  = $pageNumber;
    }

    /**
     * @param string $commonName
     * @return $this
     */
    public function setCommonName($commonName)
    {
        $this->assertNonEmptyString($commonName, 'commonName');

        $this->commonName = $commonName;

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->assertNonEmptyString($email, 'email');

        $this->email = $email;

        return $this;
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
     * @param string $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->assertNonEmptyString($role, 'role');

        $this->role = $role;

        return $this;
    }

    /**
     * @param array $secondFactorTypes
     *
     * @return void
     */
    public function setSecondFactorTypes(array $secondFactorTypes)
    {
        $this->assertAllNonEmptyString($secondFactorTypes, 'secondFactorTypes');

        $this->secondFactorTypes = $secondFactorTypes;
    }

    /**
     * @param string $orderBy
     * @return $this
     */
    public function setOrderBy($orderBy)
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @param string $orderDirection
     * @return $this
     */
    public function setOrderDirection($orderDirection)
    {
        $this->assertNonEmptyString($orderDirection, 'orderDirection');
        Assert\that($orderDirection)->choice(
            ['asc', 'desc', '', null],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection;

        return $this;
    }

    public function toHttpQuery()
    {
        return '?' . http_build_query(
            array_filter(
                [
                    'actorInstitution'  => $this->actorInstitution,
                    'institution'       => $this->institution,
                    'commonName'        => $this->commonName,
                    'email'             => $this->email,
                    'secondFactorTypes' => $this->secondFactorTypes,
                    'orderBy'           => $this->orderBy,
                    'orderDirection'    => $this->orderDirection,
                    'p'                 => $this->pageNumber,
                ],
                function ($value) {
                    return !is_null($value);
                }
            )
        );
    }

    /**
     * @param mixed       $value
     * @param string      $parameterName
     * @param string|null $message
     */
    private function assertNonEmptyString($value, $parameterName, $message = null)
    {
        $message = sprintf(
            $message ?: '"%s" must be a non-empty string, "%s" given',
            $parameterName,
            (is_object($value) ? get_class($value) : gettype($value))
        );

        Assert\that($value)->string($message)->notEmpty($message);
    }

    /**
     * @param array $values
     * @param string $parameterName
     *
     * @return void
     */
    private function assertAllNonEmptyString(array $values, $parameterName)
    {
        foreach ($values as $value) {
            $this->assertNonEmptyString(
                $value,
                $parameterName,
                'Elements of "%s" must be non-empty strings, element of type "%s" given'
            );
        }
    }
}
