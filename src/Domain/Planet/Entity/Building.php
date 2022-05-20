<?php

declare(strict_types=1);

namespace App\Domain\Planet\Entity;

use DateTime;
use DateTimeInterface;

class Building implements BuildingInterface
{
    private string $code;

    private int $level;

    private int $place;

    private ?DateTimeInterface $upgradingStartedAt = null;

    private ?DateTimeInterface $upgradingEndTime = null;

    private ?int $upgradingPlace = null;

    public function __construct(
        string $code,
        int $level,
        int $place
    ) {
        $this->code = $code;
        $this->level = $level;
        $this->place = $place;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLevel(): int
    {
        if ($this->wasRecentlyUpgraded()) {
            return $this->level+1;
        }

        return $this->level;
    }

    public function getPlace(): int
    {
        return $this->place;
    }

    private function wasRecentlyUpgraded(): bool
    {
        return $this->isUpgrading() === false && $this->upgradingStartedAt !== null;
    }

    public function startUpgrading(
        DateTimeInterface $endTime,
        int $neededPlace
    ): void {
        if ($this->wasRecentlyUpgraded()) {
            $this->finishUpgrading();
        }

        if ($this->isUpgrading()) {
            return;
        }

        $this->upgradingStartedAt = new DateTime();
        $this->upgradingEndTime = $endTime;
        $this->upgradingPlace = $neededPlace;
    }

    public function cancelUpgrading()
    {
        $this->upgradingPlace = null;
        $this->upgradingStartedAt = null;
        $this->upgradingEndTime = null;
    }

    public function isUpgrading(): bool
    {
        if ($this->upgradingStartedAt === null) {
            return false;
        }

        return $this->upgradingEndTime > new DateTime();
    }

    public function finishUpgrading(): void
    {
        if ($this->isUpgrading() === false) {
            return;
        }

        $this->level += 1;
        $this->place += $this->upgradingPlace;
        $this->upgradingPlace = null;
        $this->upgradingStartedAt = null;
        $this->upgradingEndTime = null;
    }
}