<?php

declare(strict_types=1);

namespace App\SharedKernel\Port;

interface TransactionalInterface
{
    public function beginTransaction(): void;

    public function commitTransaction(): void;

    public function rollbackTransaction(): void;
}