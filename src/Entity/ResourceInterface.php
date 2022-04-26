<?php

declare(strict_types=1);

namespace App\Entity;

interface ResourceInterface
{
    public function getCode(): ?string;
}