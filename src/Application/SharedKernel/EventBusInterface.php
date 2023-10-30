<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;
}
