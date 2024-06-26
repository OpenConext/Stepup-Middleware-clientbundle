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
use Surfnet\StepupMiddlewareClient\Identity\Dto\RecoveryToken;
use Surfnet\StepupMiddlewareClient\Identity\Dto\RecoveryTokenSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\RecoveryTokenService as LibraryRecoveryTokenService;
use Surfnet\StepupMiddlewareClientBundle\Dto\CollectionDto;
use Surfnet\StepupMiddlewareClientBundle\Exception\NotFoundException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RecoveryTokenCollection;

class RecoveryTokenService
{
    public function __construct(private readonly LibraryRecoveryTokenService $recoveryTokenService)
    {
    }

    public function hasRecoveryToken(Identity $identity): bool
    {
        return $this->recoveryTokenService->hasRecoveryToken($identity);
    }

    public function findOne(string $recoveryTokenId): RecoveryToken
    {
        try {
            return $this->recoveryTokenService->getOne($recoveryTokenId);
        } catch (RuntimeException) {
            throw new NotFoundException('Recovery Token not found ');
        }
    }

    public function findAllFor(Identity $identity): array
    {
        try {
            return $this->recoveryTokenService->getAll($identity);
        } catch (RuntimeException) {
            return [];
        }
    }

    public function getAvailableRecoveryTokenTypes(): array
    {
        return ['sms' => 'sms', 'safe-store' => 'safe-store'];
    }

    public function search(RecoveryTokenSearchQuery $query): CollectionDto
    {
        $data = $this->recoveryTokenService->search($query);
        if ($data === null) {
            return RecoveryTokenCollection::empty();
        }
        return RecoveryTokenCollection::fromData($data);
    }
}
