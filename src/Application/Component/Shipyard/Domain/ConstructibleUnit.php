<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

enum ConstructibleUnit: string
{
    case Ship = 'ship';

    case Cannon = 'cannon';
}
