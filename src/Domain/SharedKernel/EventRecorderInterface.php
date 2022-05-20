<?php

declare(strict_types=1);

namespace App\Domain\SharedKernel;

interface EventRecorderInterface
{
    public function record(Event $event): void;
}