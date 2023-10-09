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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Service;

use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;
use Surfnet\StepupMiddlewareClient\Identity\Dto\SecondFactorAuditLogSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\AuditLogService as LibraryAuditLogService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\AuditLog;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuditLogService
{
    public function __construct(private readonly LibraryAuditLogService $service, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @return AuditLog
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchSecondFactorAuditLog(SecondFactorAuditLogSearchQuery $query): ?AuditLog
    {
        $data = $this->service->searchSecondFactorAuditLog($query);

        if ($data === null) {
            return null;
        }

        $auditLog = AuditLog::fromData($data);
        $violations = $this->validator->validate($auditLog);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "One or more audit log entries retrieved from the Middleware were invalid",
                $violations
            );
        }

        return $auditLog;
    }
}
