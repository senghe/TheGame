<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

interface ResourceRequirementsInterface
{
    public function add(ResourceAmountInterface $resourceAmount): void;

    public function getAmount(ResourceIdInterface $resourceId): int;

    /** @return array<int, ResourceAmountInterface> */
    public function getAll(): array;
}
