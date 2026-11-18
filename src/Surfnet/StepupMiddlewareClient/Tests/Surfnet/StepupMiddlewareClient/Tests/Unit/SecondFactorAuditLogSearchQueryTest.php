<?php

declare(strict_types = 1);

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
use Surfnet\StepupMiddlewareClient\Identity\Dto\SecondFactorAuditLogSearchQuery;

class SecondFactorAuditLogSearchQueryTest extends TestCase
{
    public function test_it_builds_http_query_without_actor_id_for_backwards_compatibility(): void
    {
        $query = new SecondFactorAuditLogSearchQuery('institution-a.example.com', 'identity-id-123', 1);

        $expected = '?institution=institution-a.example.com&identityId=identity-id-123&orderBy=recordedOn&orderDirection=desc&p=1';
        $this->assertEquals($expected, $query->toHttpQuery());
    }

    public function test_it_builds_http_query_with_actor_id(): void
    {
        $query = new SecondFactorAuditLogSearchQuery('institution-a.example.com', 'identity-id-123', 1);
        $query->setActorId('actor-id-456');

        $expected = '?institution=institution-a.example.com&identityId=identity-id-123&orderBy=recordedOn&orderDirection=desc&p=1&actorId=actor-id-456';
        $this->assertEquals($expected, $query->toHttpQuery());
    }
}
