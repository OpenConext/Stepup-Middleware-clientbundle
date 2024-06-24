<?php

declare(strict_types = 1);

namespace Surfnet\StepupMiddlewareClient\Configuration\Dto;

use Assert;
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

class RaLocationSearchQuery implements HttpQuery
{
    private readonly string $institution;

    private string $orderBy = 'name';

    private string $orderDirection = 'asc';

    public function __construct(string $institution)
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;
    }

    /**
     * @return string
     */
    public function getInstitution(): string
    {
        return $this->institution;
    }

    /**
     * @return $this
     */
    public function setOrderBy(string $orderBy): static
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @return $this
     */
    public function setOrderDirection(string $orderDirection): static
    {
        $this->assertNonEmptyString($orderDirection, 'orderDirection');
        Assert\that($orderDirection)->choice(
            ['asc', 'desc'],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection;

        return $this;
    }

    private function assertNonEmptyString(string $value, string $name): void
    {
        $message = sprintf(
            '"%s" must be a non-empty string, "%s" given',
            $name,
            (get_debug_type($value))
        );

        Assert\that($value)->string($message)->notEmpty($message);
    }

    public function toHttpQuery(): string
    {
        return '?institution=' . urlencode($this->institution)
            . '&orderBy=' . urlencode($this->orderBy)
            . '&orderDirection' . urlencode($this->orderDirection);
    }
}
