<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain;

final class StorageId implements StorageIdInterface
{
    public function __construct(
        private readonly string $id
    ) {

    }

    public function getUuid(): string
    {
        return $this->id;
    }
}
