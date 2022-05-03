<?php

declare(strict_types=1);

namespace App\Resource\Domain\Exception;

use App\Resource\Domain\Entity\OperationInterface;
use App\Resource\Domain\Entity\SnapshotInterface;

final class OperatingOnClosedSnapshotException extends \LogicException
{
    private SnapshotInterface $snapshot;

    private OperationInterface $operation;

    public function __construct(
        SnapshotInterface $snapshot,
        OperationInterface $operation
    ) {
        parent::__construct();

        $this->snapshot = $snapshot;
        $this->operation = $operation;
    }

    public function getSnapshot(): SnapshotInterface
    {
        return $this->snapshot;
    }

    public function getOperation(): OperationInterface
    {
        return $this->operation;
    }
}
