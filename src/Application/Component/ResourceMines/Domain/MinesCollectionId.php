<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\Domain;

final class MinesCollectionId implements MinesCollectionIdInterface
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
