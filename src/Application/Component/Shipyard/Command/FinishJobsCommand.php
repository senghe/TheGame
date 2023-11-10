<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class FinishJobsCommand implements CommandInterface
{
    public function __construct(
        private readonly string $shipyardId,
    ) {

    }

    public function getShipyardId(): string
    {
        return $this->shipyardId;
    }
}
