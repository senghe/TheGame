<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Exception;
use App\SharedKernel\Port\CollectionInterface;
use LogicException;

final class ResourcesCountMismatchException extends LogicException
{
    private array $values;

    private CollectionInterface $resources;

    public function __construct(array $values, CollectionInterface $resources)
    {
        parent::__construct();

        $this->values = $values;
        $this->resources = $resources;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getResources(): CollectionInterface
    {
        return $this->resources;
    }
}