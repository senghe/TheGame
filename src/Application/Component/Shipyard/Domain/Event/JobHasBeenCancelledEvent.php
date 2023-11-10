<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Event;

use InvalidArgumentException;
use TheGame\Application\SharedKernel\EventInterface;

final class JobHasBeenCancelledEvent implements EventInterface
{
    public function __construct(
        private readonly string $shipyardId,
        private readonly string $jobId,
        private readonly string $planetId,
        private readonly array $resourceRequirements,
    ) {
        foreach ($this->resourceRequirements as $key => $value) {
            if (is_string($key) === false || is_int($value) === false) {
                throw new InvalidArgumentException('Invalid resource requirements key or value');
            }
        }
    }

    public function getShipyardId(): string
    {
        return $this->shipyardId;
    }

    public function getJobId(): string
    {
        return $this->jobId;
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    /** @return array<string, int> */
    public function getResourceRequirements(): array
    {
        return $this->resourceRequirements;
    }
}
