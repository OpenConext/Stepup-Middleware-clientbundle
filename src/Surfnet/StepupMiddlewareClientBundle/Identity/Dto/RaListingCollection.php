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

class RaListingCollection extends CollectionDto
{
    public static function fromData(array $data): self
    {
        $elements = [];
        foreach ($data['items'] as $key => $item) {
            $elements[$key] = self::createElementFromData($item);
        }

        return new self(
            $elements,
            $data['collection']['total_items'],
            $data['collection']['page'],
            $data['collection']['page_size'],
            $data['filters']
        );
    }

    protected static function createElementFromData(array $raListing): RaListing
    {
        return RaListing::fromData($raListing);
    }

    /**
     * Checks if a certain institution is listed in the RA listing
     * @param $institution
     * @return bool
     */
    public function isListed($institution): bool
    {
        /** @var RaListing $raListing */
        foreach ($this->getElements() as $raListing) {
            if ($raListing->institution === $institution) {
                return true;
            }
        }
        return false;
    }
}
