<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use TheGame\Application\Component\ResourceMines\Domain\MineIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

class Mine
{
    public function __construct(
        protected readonly MineIdInterface $id,
        protected readonly ResourceIdInterface $resourceId,
        protected int $currentMiningSpeed,
        protected DateTimeInterface $extractedAt,
    ) {

    }

    public function getId(): MineIdInterface
    {
        return $this->id;
    }

    public function isForResource(ResourceIdInterface $resourceId): bool
    {
        return $this->resourceId->getUuid() === $resourceId->getUuid();
    }

    public function upgradeMiningSpeed(int $newSpeed): void
    {
        $this->currentMiningSpeed = $newSpeed;
    }

    public function extract(): ResourceAmountInterface
    {
        $now = new DateTimeImmutable();
        $diffInSeconds = $now->getTimestamp() - $this->extractedAt->getTimestamp();
        $speedInSeconds = $this->currentMiningSpeed / 60;
        $result = $speedInSeconds * $diffInSeconds;

        $this->extractedAt = $now;

        return new ResourceAmount($this->resourceId, (int) floor($result));
    }
}
