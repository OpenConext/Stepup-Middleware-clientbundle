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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RaSecondFactorExportCollection
{
    /**
     * @Assert\Valid
     *
     * @var array
     */
    protected $elements;

    public static function fromData(array $data): self
    {
        $exportCollection = new self;

        $exportCollection->elements = [];

        foreach ($data as $secondFactor) {
            $exportCollection->elements[] = RaSecondFactor::fromData($secondFactor);
        }

        return $exportCollection;
    }

    public function count(): int
    {
        return count((array) $this->elements);
    }

    public function getColumnNames(): array
    {
        return [
            'secondFactorId' => 'Token ID',
            'type' => 'Type',
            'name' => 'Name',
            'email' => 'Email',
            'institution' => 'Institution',
            'documentNumber' => 'Document Number',
            'status' => 'Status',
        ];
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }
}
