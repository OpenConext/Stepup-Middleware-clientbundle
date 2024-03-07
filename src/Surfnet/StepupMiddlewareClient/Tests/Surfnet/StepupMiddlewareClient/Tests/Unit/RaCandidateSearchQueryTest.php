<?php
/**
 * Copyright 2024 SURFnet B.V.
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

namespace Surfnet\StepupMiddlewareClient\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Surfnet\StepupMiddlewareClient\Identity\Dto\RaCandidateSearchQuery;

class RaCandidateSearchQueryTest extends TestCase
{
    public function test_it_builds_http_query_with_all_parameters(): void
    {
        $query = new RaCandidateSearchQuery('actorId123', 1);
        $query->setCommonName('commonName123');
        $query->setEmail('email@example.com');
        $query->setInstitution('institution123');
        $query->setRaInstitution('raInstitution123');
        $query->setSecondFactorTypes(['type1', 'type2']);
        $query->setOrderBy('orderBy123');
        $query->setOrderDirection('asc');

        $expected = '?actorId=actorId123&institution=institution123&commonName=commonName123&email=email%40example.com&raInstitution=raInstitution123&secondFactorTypes%5B0%5D=type1&secondFactorTypes%5B1%5D=type2&orderBy=orderBy123&orderDirection=asc&p=1';
        $this->assertEquals($expected, $query->toHttpQuery());
    }

    public function test_it_builds_http_query_with_only_required_parameters(): void
    {
        $query = new RaCandidateSearchQuery('actorId123', 1);

        $expected = '?actorId=actorId123&p=1';
        $this->assertEquals($expected, $query->toHttpQuery());
    }

    public function test_it_builds_http_query_with_all_partial_parameters(): void
    {
        $query = new RaCandidateSearchQuery('actorId123', 1);
        $query->setCommonName('commonName123');
        $query->setRaInstitution('raInstitution123');
        $query->setSecondFactorTypes(['type1', 'type2']);
        $query->setOrderBy('orderBy123');
        $query->setOrderDirection('asc');

        $expected = '?actorId=actorId123&commonName=commonName123&raInstitution=raInstitution123&secondFactorTypes%5B0%5D=type1&secondFactorTypes%5B1%5D=type2&orderBy=orderBy123&orderDirection=asc&p=1';
        $this->assertEquals($expected, $query->toHttpQuery());
    }
}
