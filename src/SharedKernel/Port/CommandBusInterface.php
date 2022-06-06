<?php

declare(strict_types=1);

namespace App\SharedKernel\Port;

use App\SharedKernel\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}