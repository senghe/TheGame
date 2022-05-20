<?php

declare(strict_types=1);

namespace App\Domain\SharedKernel;

interface EventSubscriberInterface
{
    public function getSubscribedEvent(): string;
}