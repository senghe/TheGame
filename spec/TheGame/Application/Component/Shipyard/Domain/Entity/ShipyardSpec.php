<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Entity;

use PhpSpec\ObjectBehavior;

final class ShipyardSpec extends ObjectBehavior
{
    public function it_has_identifier(): void
    {

    }

    public function it_has_planet_id(): void
    {

    }

    public function it_has_building_id(): void
    {

    }

    public function it_queues_ships(): void
    {

    }

    public function it_throws_exception_when_trying_to_queue_more_ships_than_production_limit_allows(): void
    {

    }

    public function it_queues_cannons(): void
    {

    }

    public function it_throws_exception_when_trying_to_queue_more_cannons_than_production_limit_allows(): void
    {

    }

    public function it_calculates_production_resource_requirements_before_giving_a_job(): void
    {

    }

    public function it_returns_job_resource_requirements(): void
    {

    }

    public function it_throws_exception_when_getting_resource_requirements_of_job_which_is_not_found(): void
    {

    }

    public function it_finishes_all_jobs(): void
    {

    }

    public function it_finishes_jobs_when_having_no_job(): void
    {

    }

    public function it_finishes_only_first_job_fully(): void
    {

    }

    public function it_finishes_only_first_job_partially(): void
    {

    }

    public function it_finishes_first_job_fully_and_second_job_partially(): void
    {

    }

    public function it_finishes_both_first_and_second_jobs_fully(): void
    {

    }

    public function it_throws_exception_when_trying_to_cancel_job_but_jobs_queue_is_empty(): void
    {

    }

    public function it_throws_exception_when_trying_to_cancel_already_taken_job(): void
    {

    }

    public function it_throws_exception_when_trying_to_cancel_not_queued_job(): void
    {

    }

    public function it_cancels_job(): void
    {

    }

    public function it_upgrades_production_limit(): void
    {

    }

    public function it_returns_current_level(): void
    {

    }
}
