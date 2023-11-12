<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Entity;

use DateTimeInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\Shipyard\Domain\Exception\CantCancelCurrentlyTakenJobException;
use TheGame\Application\Component\Shipyard\Domain\Exception\ProductionLimitHasBeenReachedException;
use TheGame\Application\Component\Shipyard\Domain\Exception\ShipyardJobNotFoundException;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummary;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummaryInterface;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

class Shipyard
{
    /** @var array<int, Job> */
    private array $jobQueue = [];

    private int $currentLevel = 0;

    public function __construct(
        private readonly ShipyardIdInterface $shipyardId,
        private readonly PlanetIdInterface $planetId,
        private readonly BuildingIdInterface $buildingId,
        private int $productionLimit,
        private DateTimeInterface $lastUpdatedAt
    ) {
    }

    public function getId(): ShipyardIdInterface
    {
        return $this->shipyardId;
    }

    public function getPlanetId(): PlanetIdInterface
    {
        return $this->planetId;
    }

    public function getBuildingId(): BuildingIdInterface
    {
        return $this->buildingId;
    }

    public function getResourceRequirements(
        JobIdInterface $jobId,
    ): ResourceRequirementsInterface {
        foreach ($this->jobQueue as $job) {
            if ($job->getId()->getUuid() === $jobId->getUuid()) {
                return $job->getRequirements();
            }
        }

        throw new ShipyardJobNotFoundException($jobId);
    }

    public function queueJob(Job $job): void
    {
        if ($this->limitHasBeenReached($job->getProductionLoad(), $job->getQuantity())) {
            throw new ProductionLimitHasBeenReachedException(
                $this->productionLimit,
                $job->getQuantity()
            );
        }

        $this->jobQueue[] = $job;
    }

    private function limitHasBeenReached(
        int $productionLoad,
        int $quantity
    ): bool {
        $jobLoad = $productionLoad * $quantity;

        return $this->calculateCurrentLoad() + $jobLoad > $this->productionLimit;
    }

    private function calculateCurrentLoad(): int
    {
        $load = 0;
        foreach ($this->jobQueue as $job) {
            $load += $job->getProductionLoad();
        }

        return $load;
    }

    public function finishJobs(): FinishedJobsSummaryInterface
    {
        $summary = new FinishedJobsSummary();
        if (count($this->jobQueue) === 0) {
            return $summary;
        }

        $now = new \DateTimeImmutable();
        $elapsedTime = $now->getTimestamp() - $this->lastUpdatedAt->getTimestamp();

        do {
            if (count($this->jobQueue) === 0) {
                break;
            }

            $currentJob = $this->jobQueue[0];
            if ($currentJob->getDuration() > $elapsedTime) {
                $finishedQuantity = $currentJob->finishPartially($elapsedTime);
                $summary->addEntry(
                    $currentJob->getConstructionUnit(),
                    $currentJob->getConstructionType(),
                    $finishedQuantity,
                );

                break;
            }

            $elapsedTime -= $currentJob->getDuration();
            $currentJob->finish();

            $summary->addEntry(
                $currentJob->getConstructionUnit(),
                $currentJob->getConstructionType(),
                $currentJob->getQuantity(),
            );

            array_shift($this->jobQueue);
        } while ($elapsedTime > 0);

        $this->lastUpdatedAt = $now;

        return $summary;
    }

    public function cancelJob(JobIdInterface $jobId): void
    {
        if ($this->isIdle() === true) {
            throw new ShipyardJobNotFoundException($jobId);
        }

        if ($this->jobQueue[0]->getId()->getUuid() === $jobId->getUuid()) {
            throw new CantCancelCurrentlyTakenJobException($jobId);
        }

        foreach ($this->jobQueue as $key => $job) {
            if ($job->getId()->getUuid() === $jobId->getUuid()) {
                unset($this->jobQueue[$key]);
                $this->jobQueue = array_values($this->jobQueue);

                return;
            }
        }

        throw new ShipyardJobNotFoundException($jobId);
    }

    public function isIdle(): bool
    {
        return count($this->jobQueue) === 0;
    }

    public function getJobsCount(): int
    {
        return count($this->jobQueue);
    }

    public function hasJob(JobIdInterface $jobId): bool
    {
        foreach ($this->jobQueue as $job) {
            if ($job->getId()->getUuid() === $jobId->getUuid()) {
                return true;
            }
        }

        return false;
    }

    public function upgrade(int $newProductionLimit): void
    {
        $this->productionLimit = $newProductionLimit;
        $this->currentLevel++;
    }

    public function getCurrentLevel(): int
    {
        return $this->currentLevel;
    }
}
