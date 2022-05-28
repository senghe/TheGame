<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Entity;

use DateTime;
use DateTimeInterface;

class Building implements BuildingInterface
{
    private string $code;

    private int $level;

    private ?DateTimeInterface $upgradingStartedAt = null;

    private ?DateTimeInterface $upgradingEndTime = null;

    public function __construct(
        string $code,
        int $level
    ) {
        $this->code = $code;
        $this->level = $level;
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

    private function wasRecentlyUpgraded(): bool
    {
        return $this->isUpgrading() === false && $this->upgradingStartedAt !== null;
    }

    public function startUpgrading(
        DateTimeInterface $endTime
    ): void {
        if ($this->wasRecentlyUpgraded()) {
            $this->finishUpgrading();
        }

        if ($this->isUpgrading()) {
            return;
        }

        $this->upgradingStartedAt = new DateTime();
        $this->upgradingEndTime = $endTime;
    }

    public function cancelUpgrading()
    {
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
        $this->upgradingStartedAt = null;
        $this->upgradingEndTime = null;
    }
}