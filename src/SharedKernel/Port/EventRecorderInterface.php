<?php

declare(strict_types=1);

namespace App\SharedKernel\Port;

use App\SharedKernel\EventInterface;

interface EventRecorderInterface
{
    public function record(EventInterface $event): void;
}