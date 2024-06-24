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

namespace Surfnet\StepupMiddlewareClient\Service;

class ExecutionResult
{
    /**
     * @param string|null $uuid Null in case of errors.
     * @param string|null $processedBy Null in case of errors.
     */
    public function __construct(private ?string $uuid, private ?string $processedBy, private readonly array $errors = [])
    {
    }

    /**
     * @return string|null Null in case of errors.
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return null|string Null in case of errors.
     */
    public function getProcessedBy(): ?string
    {
        return $this->processedBy;
    }

    /**
     * @return bool False in case of errors.
     */
    public function isSuccessful(): bool
    {
        return $this->errors === [];
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
