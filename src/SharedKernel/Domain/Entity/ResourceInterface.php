<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Entity;

interface ResourceInterface
{
    public function getId(): int;

    public function getCode(): string;
}
