<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners\Domain;

final class MineId implements MineIdInterface
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
