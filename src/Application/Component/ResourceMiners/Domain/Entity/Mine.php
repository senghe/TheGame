<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use TheGame\Application\Component\ResourceMiners\Domain\MineIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

class Mine
{
    protected int $currentLevel;

    public function __construct(
        protected readonly MineIdInterface $id,
        protected readonly ResourceIdInterface $resourceId,
        protected float $baseMiningSpeed,
        protected float $currentMiningSpeed,
        protected float $miningMultiplier,
        protected DateTimeInterface $extractedAt,
    ) {
        $this->currentLevel = 1;
    }

    public function getId(): MineIdInterface
    {
        return $this->id;
    }

    public function upgrade(): void
    {
        $this->currentMiningSpeed = $this->baseMiningSpeed * $this->miningMultiplier * $this->currentLevel;
    }

    public function extract(): ResourceAmount
    {
        $now = new DateTimeImmutable();
        $diffInSeconds = $now->getTimestamp() - $this->extractedAt->getTimestamp();
        $speedInSeconds = $this->currentMiningSpeed / 60;
        $result = $speedInSeconds * $diffInSeconds;

        $this->extractedAt = $now;

        return new ResourceAmount($this->resourceId, (int) floor($result));
    }
}