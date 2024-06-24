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

namespace Surfnet\StepupMiddlewareClientBundle\Dto;

use LogicException;

abstract class CollectionDto implements Dto
{
    protected int $totalItems;

    protected int $currentPage;

    protected int $itemsPerPage;

    public function __construct(
        protected array $elements,
        int $totalItems,
        int $currentPage,
        int $itemsPerPage,
        private array $filterOptions = []
    ) {
        $this->totalItems = $totalItems;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
    }

    public static function fromData(array $data): self
    {
        $elements = [];
        foreach ($data['items'] as $key => $item) {
            $elements[$key] = static::createElementFromData($item);
        }

        return new static(
            $elements,
            $data['collection']['total_items'],
            $data['collection']['page'],
            $data['collection']['page_size'],
            $data['filters']
        );
    }

    public static function empty(): static
    {
        return new static([], 0, 1, 1);
    }

    /**
     * Load the element in the collection based on the data given
     *
     * @param  array $item
     * @return mixed
     */
    protected static function createElementFromData(array $item): mixed
    {
        throw new LogicException('The method "%s::createElementFromData must be implemented to load the Collection Element"');
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function getFilterOption(string $key): array
    {
        if (!array_key_exists($key, $this->filterOptions)) {
            return [];
        }
        return $this->filterOptions[$key];
    }

    /**
     * @return mixed|null
     * @throws LogicException When there is more than 1 element present.
     */
    public function getOnlyElement(): mixed
    {
        $elementCount = count($this->elements);

        if ($elementCount === 1) {
            return reset($this->elements);
        } elseif ($elementCount === 0) {
            return null;
        }

        throw new LogicException(sprintf('There are %d elements in this collection instead of one.', $elementCount));
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
