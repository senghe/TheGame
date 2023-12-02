<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotMergeShipGroupsOfDifferentTypeException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroupInterface;

final class ShipsGroupSpec extends ObjectBehavior
{
    public function let(): void
    {
        $type = "light-fighter";
        $quantity = 10;
        $speed = 35;
        $unitLoadCapacity = 20;

        $this->beConstructedWith($type, $quantity, $speed, $unitLoadCapacity);
    }

    public function it_has_type(): void
    {
        $this->getType()->shouldReturn("light-fighter");
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()->shouldReturn(10);
    }

    public function it_checks_the_correct_type(): void
    {
        $this->hasType("light-fighter")->shouldReturn(true);
    }

    public function it_checks_the_incorrect_type(): void
    {
        $this->hasType("warship")->shouldReturn(false);
    }

    public function it_checks_whether_has_more_ships_than_quantity(): void
    {
        $this->hasMoreShipsThan(5)->shouldReturn(true);
    }

    public function it_checks_whether_has_more_ships_than_a_quantity_but_has_not(): void
    {
        $this->hasMoreShipsThan(15)->shouldReturn(false);
    }

    public function it_checks_whether_has_enough_ships(): void
    {
        $this->hasEnoughShips(5)->shouldReturn(true);
    }

    public function it_checks_whether_has_enough_ships_but_has_not(): void
    {
        $this->hasEnoughShips(15)->shouldReturn(false);
    }

    public function it_merges_ships(
        ShipsGroupInterface $shipsGroup,
    ): void {
        $shipsGroup->getType()->willReturn('light-fighter');
        $shipsGroup->getQuantity()->willReturn(15);
        $shipsGroup->setEmpty()->shouldBeCalledOnce();

        $this->merge($shipsGroup);
        $this->getQuantity()->shouldReturn(25);
    }

    public function it_throws_exception_on_merging_ships_of_unsupported_type(
        ShipsGroupInterface $shipsGroup,
    ): void {
        $shipsGroup->getType()->willReturn('warship');

        $this->shouldThrow(CannotMergeShipGroupsOfDifferentTypeException::class)->during('merge', [$shipsGroup]);
    }

    public function it_splits_into_second_group(): void
    {
        $secondGroup = $this->split(5);
        $secondGroup->getType()->shouldReturn('light-fighter');
        $secondGroup->getQuantity()->shouldReturn(5);
        $secondGroup->getSpeed()->shouldReturn(35);
        $secondGroup->getUnitLoadCapacity()->shouldReturn(20);

        $this->getQuantity()->shouldReturn(5);
    }

    public function it_throws_exception_on_splitting_when_hasnt_enough_ships(): void
    {
        $this->shouldThrow(NotEnoughShipsException::class)->during('split', [15]);
    }

    public function it_has_speed(): void
    {
        $this->getSpeed()->shouldReturn(35);
    }

    public function it_has_load_capacity(): void
    {
        $this->getLoadCapacity()->shouldReturn(200);
    }

    public function it_has_load_capacity_of_single_ship(): void
    {
        $this->getUnitLoadCapacity()->shouldReturn(20);
    }

    public function it_sets_group_empty(): void
    {
        $this->setEmpty();

        $this->isEmpty()->shouldReturn(true);
    }

    public function it_is_not_empty(): void
    {
        $this->isEmpty()->shouldReturn(false);
    }
}
