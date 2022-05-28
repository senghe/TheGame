<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Exception;

use App\Component\Resource\Domain\Entity\SnapshotInterface;

final class WorkingOnEmptySnapshotException extends \LogicException
{
    private SnapshotInterface $snapshot;

    public function __construct(
        SnapshotInterface $snapshot
    ) {
        parent::__construct();

        $this->snapshot = $snapshot;
    }

    public function getSnapshot(): SnapshotInterface
    {
        return $this->snapshot;
    }
}
