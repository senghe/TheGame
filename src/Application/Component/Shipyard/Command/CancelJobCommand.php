<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class CancelJobCommand implements CommandInterface
{
    public function __construct(
        private readonly string $shipyardId,
        private readonly string $jobId,
    ) {

    }

    public function getShipyardId(): string
    {
        return $this->shipyardId;
    }

    public function getJobId(): string
    {
        return $this->jobId;
    }
}
