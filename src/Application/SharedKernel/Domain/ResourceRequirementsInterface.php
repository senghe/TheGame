<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

interface ResourceRequirementsInterface
{
    public function add(ResourceAmountInterface $resourceAmount): void;

    public function getAmount(ResourceIdInterface $resourceId): int;

    /** @return array<string, int> */
    public function toScalarArray(): array;

    /** @return array<int, ResourceAmountInterface> */
    public function getAll(): array;

    public function multipliedBy(int $quantity): ResourceRequirementsInterface;
}
