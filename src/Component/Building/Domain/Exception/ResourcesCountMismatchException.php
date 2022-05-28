<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Exception;

use Doctrine\Common\Collections\Collection;
use LogicException;

final class ResourcesCountMismatchException extends LogicException
{
    private array $values;

    private Collection $resources;

    public function __construct(array $values, Collection $resources)
    {
        parent::__construct();

        $this->values = $values;
        $this->resources = $resources;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getResources(): Collection
    {
        return $this->resources;
    }
}