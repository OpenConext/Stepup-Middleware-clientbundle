<?php

declare(strict_types = 1);

/**
 * Copyright 2022 SURFnet bv
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

use Surfnet\StepupMiddlewareClient\Exception\RuntimeException;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VettingTypeHint;
use Surfnet\StepupMiddlewareClient\Identity\Service\VettingTypeHintService as LibraryVettingTypeHintService;
use Surfnet\StepupMiddlewareClientBundle\Exception\NotFoundException;

class VettingTypeHintService
{
    public function __construct(private readonly LibraryVettingTypeHintService $vettingTypeHintService)
    {
    }

    public function findOne(string $institution): VettingTypeHint
    {
        try {
            return $this->vettingTypeHintService->getOne($institution);
        } catch (RuntimeException $e) {
            throw new NotFoundException(
                sprintf('Vetting type hint not found for institution %s', $institution),
                $e->getCode(),
                $e->getPrevious()
            );
        }
    }
}
