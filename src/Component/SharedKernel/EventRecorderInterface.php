<?php

declare(strict_types=1);

namespace App\Component\SharedKernel;

interface EventRecorderInterface
{
    public function record(Event $event): void;
}