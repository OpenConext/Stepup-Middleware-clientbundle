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

use Surfnet\StepupMiddlewareClientBundle\Dto\CollectionDto;

class InstitutionListingCollection extends CollectionDto
{
    public static function fromData(array $data): self
    {
        $elements = [];
        foreach ($data as $item) {
            $elements[] = static::createElementFromData($item);
        }

        return new self(
            $elements,
            count($elements),
            1,
            count($elements)
        );
    }

    protected static function createElementFromData(array $item): InstitutionListing
    {
        return InstitutionListing::fromData($item);
    }
}
