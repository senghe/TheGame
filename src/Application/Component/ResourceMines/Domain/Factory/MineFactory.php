<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\Domain\Factory;

use DateTimeImmutable;
use TheGame\Application\Component\Balance\Bridge\ResourceMinesContextInterface;
use TheGame\Application\Component\ResourceMines\Domain\Entity\Mine;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class MineFactory implements MineFactoryInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly ResourceMinesContextInterface $resourceMinesContext,
    ) {
    }

    public function createNew(ResourceIdInterface $resourceId): Mine
    {
        $mineId = $this->uuidGenerator->generateNewMineId();
        $initialMiningSpeed = $this->resourceMinesContext->getMiningSpeed(1, $resourceId);

        return new Mine(
            $mineId,
            $resourceId,
            $initialMiningSpeed,
            new DateTimeImmutable(),
        );
    }
}
