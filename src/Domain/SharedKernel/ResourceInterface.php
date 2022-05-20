<?php

declare(strict_types=1);

namespace App\Domain\SharedKernel;

interface ResourceInterface
{
    public function getCode(): ?string;
}
