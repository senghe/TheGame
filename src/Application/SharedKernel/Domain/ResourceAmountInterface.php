<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;

interface ResourceAmountInterface
{
    public function getResourceId(): ResourceIdInterface;

    public function getAmount(): int;
}
