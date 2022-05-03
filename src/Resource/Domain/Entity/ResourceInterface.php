<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

interface ResourceInterface
{
    public function getId(): int;

    public function getCode(): ?string;

    public function is(ResourceInterface $resource): bool;
}
