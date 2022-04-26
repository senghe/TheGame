<?php

declare(strict_types=1);

namespace App\Entity;

class Resource implements ResourceInterface
{
    protected int $id;

    protected ?string $code;

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }
}