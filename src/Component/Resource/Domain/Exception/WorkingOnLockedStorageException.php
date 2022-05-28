<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Exception;

use App\Component\Resource\Domain\Entity\StorageInterface;

final class WorkingOnLockedStorageException extends \LogicException
{
    private StorageInterface $storage;

    public function __construct(
        StorageInterface $storage
    ) {
        parent::__construct();

        $this->storage = $storage;
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }
}
