<?php

declare(strict_types=1);

namespace App\Domain\Resource\Entity;

use \App\Domain\SharedKernel\ResourceInterface as BaseResourceInterface;

interface ResourceInterface extends BaseResourceInterface
{
    public function getId(): int;

    public function is(ResourceInterface $resource): bool;
}
