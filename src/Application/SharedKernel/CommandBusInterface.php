<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
