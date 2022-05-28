<?php

declare(strict_types=1);

namespace App\Component\SharedKernel;

interface EventSubscriberInterface
{
    public function getSubscribedEvent(): string;
}