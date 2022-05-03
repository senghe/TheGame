<?php

declare(strict_types=1);

namespace App\Resource\Domain\Factory;

use App\Resource\Domain\Entity\ResourceInterface;
use App\Resource\Domain\Entity\SnapshotInterface;
use App\Resource\Domain\Entity\Storage;
use App\Resource\Domain\Entity\StorageInterface;

final class StorageFactory implements StorageFactoryInterface
{
    private const AMOUNT_AT_BEGINNING = 250;

    private const INCREASE_SPEED_AT_BEGINNING = 50;

    private const MAX_AMOUNT_AT_BEGINNING = 500;

    public function create(
        SnapshotInterface $snapshot,
        ResourceInterface $resource
    ): StorageInterface {
        return new Storage(
            $resource,
            self::AMOUNT_AT_BEGINNING,
            self::INCREASE_SPEED_AT_BEGINNING,
            self::MAX_AMOUNT_AT_BEGINNING
        );
    }
}
