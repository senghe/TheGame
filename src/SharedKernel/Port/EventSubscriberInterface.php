<?php

declare(strict_types=1);

namespace App\SharedKernel\Port;

interface EventSubscriberInterface
{
    public function getSubscribedEvent(): string;
}