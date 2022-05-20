<?php

declare(strict_types=1);

namespace App\Domain\Resource\Exception;

use App\Domain\Resource\Entity\ResourceInterface;
use LogicException;

final class NoValueFoundForResourceException extends LogicException
{
    private ResourceInterface $resource;

    public function __construct(ResourceInterface $resource)
    {
        parent::__construct();

        $this->resource = $resource;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }
}
