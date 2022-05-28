<?php

declare(strict_types=1);

namespace App\Component\SharedKernel\Domain;

interface ResourceInterface
{
    public function getId(): int;

    public function getCode(): string;
}
