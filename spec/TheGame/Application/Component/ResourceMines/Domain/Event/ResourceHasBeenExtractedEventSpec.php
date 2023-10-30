<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMines\Domain\Event;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\EventInterface;

final class ResourceHasBeenExtractedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "d8391d08-7c2e-40d8-b60c-a7c982e5e19e";
        $resourceId = "89b0c562-ca99-44e9-a9d1-22cb235352bb";
        $amount = 10;

        $this->beConstructedWith($planetId, $resourceId, $amount);
    }

    public function it_is_an_event(): void
    {
        $this->shouldImplement(EventInterface::class);
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
}
