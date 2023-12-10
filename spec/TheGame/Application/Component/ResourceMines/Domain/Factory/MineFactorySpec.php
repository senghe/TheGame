<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMines\Domain\Factory;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Balance\Bridge\ResourceMinesContextInterface;
use TheGame\Application\Component\ResourceMines\Domain\Entity\Mine;
use TheGame\Application\Component\ResourceMines\Domain\MineIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class MineFactorySpec extends ObjectBehavior
{
    public function let(
        UuidGeneratorInterface $uuidGenerator,
        ResourceMinesContextInterface $resourceMinesContext,
    ): void {
        $this->beConstructedWith(
            $uuidGenerator,
            $resourceMinesContext,
        );
    }

    public function it_creates_new_mine_for_resource(
        ResourceIdInterface $resourceId,
        MineIdInterface $mineId,
        UuidGeneratorInterface $uuidGenerator,
        ResourceMinesContextInterface $resourceMinesContext,
    ): void {
        $uuidGenerator->generateNewMineId()
            ->willReturn($mineId);

        $resourceMinesContext->getMiningSpeed(1, $resourceId)
            ->willReturn(500);

        $mine = $this->createNew($resourceId);

        $mine->shouldHaveType(Mine::class);
        $mine->getId()->shouldReturn($mineId);
    }
}
