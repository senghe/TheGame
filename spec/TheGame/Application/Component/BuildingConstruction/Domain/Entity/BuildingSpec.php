<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Entity;

use PhpSpec\ObjectBehavior;

final class BuildingSpec extends ObjectBehavior
{
    public function it_has_identifier(): void
    {

    }

    public function it_has_planet_identifier(): void
    {

    }

    public function it_has_current_level(): void
    {

    }

    public function it_has_a_type(): void
    {

    }

    public function it_starts_upgrading(): void
    {

    }

    public function it_throws_exception_when_starts_upgrading_building_which_is_already_during_upgrade(): void
    {

    }

    public function it_cancels_upgrading(): void
    {

    }

    public function it_throws_exception_when_cancels_upgrading_a_building_which_is_not_during_upgrade(): void
    {

    }

    public function it_finishes_upgrading_building(): void
    {

    }

    public function it_throws_exception_when_finishes_upgrading_a_building_which_is_not_during_upgrade(): void
    {

    }

    public function it_throws_exception_when_finishes_upgrading_a_building_when_upgrade_time_didnt_pass(): void
    {

    }

    public function it_has_resource_context_id(): void
    {

    }

    public function it_has_no_resource_context_id(): void
    {

    }
}
