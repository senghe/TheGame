<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

interface ResourcesInterface
{
    public function add(ResourcesInterface $resources): void;

    public function addResource(ResourceAmountInterface $resourceAmount): void;

    public function getAmount(ResourceIdInterface $resourceId): int;

    /** @param array<string, int> $scalarArray */
    public static function fromScalarArray(array $scalarArray): self;

    /** @return array<string, int> */
    public function toScalarArray(): array;

    /** @return array<int, ResourceAmountInterface> */
    public function getAll(): array;

    public function multipliedBy(int $quantity): ResourcesInterface;

    public function sum(): int;

    public function clear(): void;
}
