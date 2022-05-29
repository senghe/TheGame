<?php

declare(strict_types=1);

namespace App\Component\SharedKernel;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}