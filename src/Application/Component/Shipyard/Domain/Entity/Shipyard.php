<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Entity;

use DateTimeInterface;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleInterface;
use TheGame\Application\Component\Shipyard\Domain\Exception\CantCancelCurrentlyTakenJobException;
use TheGame\Application\Component\Shipyard\Domain\Exception\ProductionLimitHasBeenReachedException;
use TheGame\Application\Component\Shipyard\Domain\Exception\ShipyardJobNotFoundException;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummary;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;
use TheGame\Application\Component\Shipyard\Domain\ValueObject\Cannon;
use TheGame\Application\Component\Shipyard\Domain\ValueObject\Ship;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

class Shipyard
{
    /** @var array<int, Job> */
    private array $jobQueue;

    public function __construct(
        private readonly ShipyardIdInterface $shipyardId,
        private readonly PlanetIdInterface $planetId,
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

    public function queueShips(
        Ship $ship,
        int $quantity,
    ): void {
        $this->queueJob($ship, $quantity);
    }

    public function queueCannons(
        Cannon $cannon,
        int $quantity,
    ): void {
        $this->queueJob($cannon, $quantity);
    }

    public function calculateResourceRequirements(
        ConstructibleInterface $constructible,
        int $quantity,
    ): ResourceRequirementsInterface {
        return $constructible->getRequirements()->multipliedBy($quantity);
    }

    public function getResourceRequirements(
        JobIdInterface $jobId,
    ): ResourceRequirementsInterface {
        foreach ($this->jobQueue as $job) {
            if ($job->getId()->getUuid() === $jobId) {
                return $job->getRequirements();
            }
        }

        throw new ShipyardJobNotFoundException($jobId);
    }

    private function queueJob(
        ConstructibleInterface $constructible,
        int $quantity,
    ): void {
        if ($this->limitHasBeenReached($constructible, $quantity)) {
            throw new ProductionLimitHasBeenReachedException($this->productionLimit, $quantity);
        }

        $this->jobQueue[] = $constructible;
    }

    private function limitHasBeenReached(
        ConstructibleInterface $constructible,
        int $quantity
    ): bool {
        $jobLoad = $constructible->getProductionLoad() * $quantity;

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

    public function finishJobs(): FinishedJobsSummary
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
        } while($elapsedTime > 0);

        $this->lastUpdatedAt = $now;

        return $summary;
    }

    public function cancelJob(JobIdInterface $jobId): void
    {
        if (count($this->jobQueue)) {
            throw new ShipyardJobNotFoundException($jobId);
        }

        if ($this->jobQueue[0]->getId() === $jobId) {
            throw new CantCancelCurrentlyTakenJobException($jobId);
        }

        foreach ($this->jobQueue as $key => $job) {
            if ($job->getId() === $jobId) {
                unset($this->jobQueue[$key]);
                $this->jobQueue = array_values($this->jobQueue);

                return;
            }
        }

        throw new ShipyardJobNotFoundException($jobId);
    }
}
