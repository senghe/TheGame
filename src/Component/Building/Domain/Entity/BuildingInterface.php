<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Entity;

use DateTimeInterface;

interface BuildingInterface
{
    public const CODE_METAL_MINE = 'metal-mine';

    public const CODE_METAL_STORE = 'metal-store';

    public const CODE_MINERAL_MINE = 'mineral-mine';

    public const CODE_MINERAL_STORE = 'mineral-store';

    public const CODE_GAS_MINE = 'gas-mine';

    public const CODE_GAS_STORE = 'gas-store';

    public const CODE_GOLD_MINE = 'gold-mine';

    public const CODE_GOLD_STORE = 'gold-store';

    public const CODE_SOLAR_POWER_STATION = 'solar-power-station';

    public const CODE_FUSION_REACTOR = 'fusion-power-station';

    public const CODE_NUCLEAR_REACTOR = 'nuclear-power-station';

    public const CODE_FLEET_BEACON = 'fleet-beacon';

    public const CODE_LABORATORY = 'laboratory';

    public const CODE_ROBOTICS_FACILITY = 'robotics-facility';

    public const CODE_NANITE_FACILITY = 'nanite-facility';

    public const CODE_TERRAFORMER = 'terraformer';

    public function getCode(): string;

    public function getLevel(): int;

    public function startUpgrading(
        DateTimeInterface $endTime
    ): void;

    public function cancelUpgrading();

    public function isUpgrading(): bool;

    public function finishUpgrading(): void;
}