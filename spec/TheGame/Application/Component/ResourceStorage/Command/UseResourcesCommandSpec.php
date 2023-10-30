<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\Command;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\ResourceStorage\Exception\InvalidUseAmountException;
use TheGame\Application\SharedKernel\CommandInterface;

final class UseResourcesCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "d8391d08-7c2e-40d8-b60c-a7c982e5e19e";
        $resourceId = "89b0c562-ca99-44e9-a9d1-22cb235352bb";
        $amount = 10;

        $this->beConstructedWith($planetId, $resourceId, $amount);
    }

    public function it_is_a_command(): void
    {
        $this->shouldImplement(CommandInterface::class);
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("d8391d08-7c2e-40d8-b60c-a7c982e5e19e");
    }

    public function it_has_resource_id(): void
    {
        $this->getResourceId()->shouldReturn("89b0c562-ca99-44e9-a9d1-22cb235352bb");
    }

    public function it_has_amount(): void
    {
        $this->getAmount()->shouldReturn(10);
    }

    public function it_throws_exception_when_amount_is_less_than_zero(): void
    {
        $planetId = "d8391d08-7c2e-40d8-b60c-a7c982e5e19e";
        $resourceId = "89b0c562-ca99-44e9-a9d1-22cb235352bb";
        $amount = -10;

        $this->beConstructedWith($planetId, $resourceId, $amount);

        $this->shouldThrow(InvalidUseAmountException::class)
            ->during('__construct', [$planetId, $resourceId, $amount]);
    }

    public function it_throws_exception_when_amount_is_zero(): void
    {
        $planetId = "d8391d08-7c2e-40d8-b60c-a7c982e5e19e";
        $resourceId = "89b0c562-ca99-44e9-a9d1-22cb235352bb";
        $amount = 0;

        $this->beConstructedWith($planetId, $resourceId, $amount);

        $this->shouldThrow(InvalidUseAmountException::class)
            ->during('__construct', [$planetId, $resourceId, $amount]);
    }
}
