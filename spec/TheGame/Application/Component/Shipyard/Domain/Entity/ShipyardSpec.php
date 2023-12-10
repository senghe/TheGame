<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Entity;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\Entity\Job;
use TheGame\Application\Component\Shipyard\Domain\Exception\CantCancelCurrentlyTakenJobException;
use TheGame\Application\Component\Shipyard\Domain\Exception\ProductionLimitHasBeenReachedException;
use TheGame\Application\Component\Shipyard\Domain\Exception\ShipyardJobNotFoundException;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

final class ShipyardSpec extends ObjectBehavior
{
    public function let(): void
    {
        $shipyardId = "3B899AFA-BE0D-4A1C-8557-D555E5EFF298";
        $planetId = "7DCABA4D-37D6-40D7-8DB6-C18D225929FD";
        $buildingId = "2EC4155E-2049-4980-8616-8BD85F7B7BF2";
        $productionLimit = 15;
        $lastUpdatedAt = new DateTimeImmutable("now - 30 seconds");

        $this->beConstructedWith(
            new ShipyardId($shipyardId),
            new PlanetId($planetId),
            new BuildingId($buildingId),
            $productionLimit,
            $lastUpdatedAt,
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->shouldReturnAnInstanceOf(ShipyardId::class);
        $this->getId()->getUuid()->shouldReturn("3B899AFA-BE0D-4A1C-8557-D555E5EFF298");
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturnAnInstanceOf(PlanetId::class);
        $this->getPlanetId()->getUuid()->shouldReturn("7DCABA4D-37D6-40D7-8DB6-C18D225929FD");
    }

    public function it_has_building_id(): void
    {
        $this->getBuildingId()->shouldReturnAnInstanceOf(BuildingId::class);
        $this->getBuildingId()->getUuid()->shouldReturn("2EC4155E-2049-4980-8616-8BD85F7B7BF2");
    }

    public function it_queues_job(
        Job $job
    ): void {
        $job->getProductionLoad()->willReturn(3);
        $job->getQuantity()->willReturn(1);
        $this->queueJob($job);
    }

    public function it_throws_exception_when_trying_to_queue_more_ships_than_production_limit_allows(
        Job $job
    ): void {
        $job->getProductionLoad()->willReturn(30);
        $job->getQuantity()->willReturn(1);

        $this->shouldThrow(ProductionLimitHasBeenReachedException::class)->during('queueJob', [$job]);
    }

    public function it_returns_job_resource_requirements(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            5,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            5,
            $job2ResourceRequirements,
        );

        $this->queueJob($job1);
        $this->queueJob($job2);

        $this->getResourceRequirements($job1Id)->shouldReturn($job1ResourceRequirements);
        $this->getResourceRequirements($job2Id)->shouldReturn($job2ResourceRequirements);
    }

    public function it_throws_exception_when_getting_resource_requirements_of_job_which_is_not_found(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
        JobIdInterface $job3Id,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            5,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            5,
            $job2ResourceRequirements,
        );

        $this->queueJob($job1);
        $this->queueJob($job2);

        $job3Id->getUuid()->willReturn("EFC5144F-934D-4FF9-BD9F-FB5031FB1FD8");

        $this->shouldThrow(ShipyardJobNotFoundException::class)
            ->during('getResourceRequirements', [$job3Id]);
    }

    public function it_finishes_all_jobs(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            5,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            5,
            $job2ResourceRequirements,
        );

        $job1->finish()->shouldBeCalledOnce();
        $job2->finish()->shouldBeCalledOnce();

        $this->queueJob($job1);
        $this->queueJob($job2);

        $summary = $this->finishJobs();
        $summary->getSummary()->shouldHaveCount(2);

        $this->isIdle()->shouldReturn(true);
        $this->getJobsCount()->shouldReturn(0);
        $this->hasJob($job1Id)->shouldReturn(false);
        $this->hasJob($job2Id)->shouldReturn(false);
    }

    public function it_finishes_jobs_when_having_no_job(): void
    {
        $summary = $this->finishJobs();
        $summary->getSummary()->shouldHaveCount(0);

        $this->isIdle()->shouldReturn(true);
        $this->getJobsCount()->shouldReturn(0);
    }

    public function it_finishes_only_first_job_fully(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            30,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            500,
            $job2ResourceRequirements,
        );

        $job1->finish()->shouldBeCalledOnce();

        $this->queueJob($job1);
        $this->queueJob($job2);

        $summary = $this->finishJobs();
        $summary->getSummary()->shouldHaveCount(1);

        $this->isIdle()->shouldReturn(false);
        $this->getJobsCount()->shouldReturn(1);
        $this->hasJob($job1Id)->shouldReturn(false);
        $this->hasJob($job2Id)->shouldReturn(true);
    }

    public function it_finishes_only_first_job_partially(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            50,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            5,
            $job2ResourceRequirements,
        );

        $job1->finishPartially(30)->shouldBeCalledOnce();

        $this->queueJob($job1);
        $this->queueJob($job2);

        $summary = $this->finishJobs();
        $summary->getSummary()->shouldHaveCount(1);

        $this->isIdle()->shouldReturn(false);
        $this->getJobsCount()->shouldReturn(2);
        $this->hasJob($job1Id)->shouldReturn(true);
        $this->hasJob($job2Id)->shouldReturn(true);
    }

    public function it_finishes_first_job_fully_and_second_job_partially(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            20,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            50,
            $job2ResourceRequirements,
        );

        $job1->finish()->shouldBeCalledOnce();
        $job2->finishPartially(10)->shouldBeCalledOnce();

        $this->queueJob($job1);
        $this->queueJob($job2);

        $summary = $this->finishJobs();
        $summary->getSummary()->shouldHaveCount(2);

        $this->isIdle()->shouldReturn(false);
        $this->getJobsCount()->shouldReturn(1);
        $this->hasJob($job1Id)->shouldReturn(false);
        $this->hasJob($job2Id)->shouldReturn(true);
    }

    public function it_finishes_both_first_and_second_jobs_fully(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            10,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            10,
            $job2ResourceRequirements,
        );

        $job1->finish()->shouldBeCalledOnce();
        $job2->finish()->shouldBeCalledOnce();

        $this->queueJob($job1);
        $this->queueJob($job2);

        $summary = $this->finishJobs();
        $summary->getSummary()->shouldHaveCount(2);

        $this->isIdle()->shouldReturn(true);
        $this->getJobsCount()->shouldReturn(0);
        $this->hasJob($job1Id)->shouldReturn(false);
        $this->hasJob($job2Id)->shouldReturn(false);
    }

    private function stubTwoJobs(
        Job $job1,
        JobIdInterface $job1Id,
        int $duration1,
        ResourcesInterface $job1ResourceRequirements,
        Job $job2,
        JobIdInterface $job2Id,
        int $duration2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $job1Id->getUuid()->willReturn("CF224D29-FEAE-45C1-9C69-D6D8D92110BC");
        $job1->getId()->willReturn($job1Id);
        $job1->getDuration()->willReturn($duration1);
        $job1->getProductionLoad()->willReturn(3);
        $job1->getQuantity()->willReturn(1);
        $job1->getConstructionUnit()->willReturn(ConstructibleUnit::Ship);
        $job1->getConstructionType()->willReturn('light-fighter');
        $job1->getRequirements()->willReturn($job1ResourceRequirements);

        $job2Id->getUuid()->willReturn("38A1A7BA-225D-4C58-BF24-8645ECD768A7");
        $job2->getId()->willReturn($job2Id);
        $job2->getDuration()->willReturn($duration2);
        $job2->getProductionLoad()->willReturn(3);
        $job2->getQuantity()->willReturn(1);
        $job2->getConstructionUnit()->willReturn(ConstructibleUnit::Cannon);
        $job2->getConstructionType()->willReturn('laser');
        $job2->getRequirements()->willReturn($job2ResourceRequirements);
    }

    public function it_throws_exception_when_trying_to_cancel_job_but_jobs_queue_is_empty(
        JobIdInterface $jobId,
    ): void {
        $jobId->getUuid()->willReturn("60446888-CF73-4FD0-8862-E70E56320622");

        $this->shouldThrow(ShipyardJobNotFoundException::class)
            ->during('cancelJob', [$jobId]);
    }

    public function it_throws_exception_when_trying_to_cancel_already_taken_job(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            10,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            10,
            $job2ResourceRequirements,
        );
        $job1Id->getUuid()->willReturn("60446888-CF73-4FD0-8862-E70E56320622");

        $this->queueJob($job1);
        $this->queueJob($job2);

        $this->shouldThrow(CantCancelCurrentlyTakenJobException::class)
            ->during('cancelJob', [$job1Id]);
    }

    public function it_throws_exception_when_trying_to_cancel_not_queued_job(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
        JobIdInterface $notQueuedJobId
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            10,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            10,
            $job2ResourceRequirements,
        );
        $job1Id->getUuid()->willReturn("60446888-CF73-4FD0-8862-E70E56320622");
        $notQueuedJobId->getUuid()->willReturn("1F8BC672-41E5-4A24-9D07-044198E01196");

        $this->queueJob($job1);
        $this->queueJob($job2);

        $this->shouldThrow(ShipyardJobNotFoundException::class)
            ->during('cancelJob', [$notQueuedJobId]);
    }

    public function it_cancels_job(
        JobIdInterface $job1Id,
        Job $job1,
        ResourcesInterface $job1ResourceRequirements,
        JobIdInterface $job2Id,
        Job $job2,
        ResourcesInterface $job2ResourceRequirements,
        JobIdInterface $notQueuedJobId
    ): void {
        $this->stubTwoJobs(
            $job1,
            $job1Id,
            10,
            $job1ResourceRequirements,
            $job2,
            $job2Id,
            10,
            $job2ResourceRequirements,
        );

        $this->queueJob($job1);
        $this->queueJob($job2);

        $this->cancelJob($job2Id);
    }

    public function it_upgrades_production_limit(): void
    {
        $this->upgrade(500);
        $this->getCurrentLevel()->shouldReturn(1);

        $this->upgrade(750);
        $this->getCurrentLevel()->shouldReturn(2);
    }

    public function it_returns_current_level(): void
    {
        $this->getCurrentLevel()->shouldReturn(0);
    }
}
