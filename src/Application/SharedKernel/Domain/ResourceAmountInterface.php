<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

interface ResourceAmountInterface
{
    public function getResourceId(): ResourceIdInterface;

    public function getAmount(): int;
}
