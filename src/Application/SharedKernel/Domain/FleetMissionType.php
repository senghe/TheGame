<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

enum FleetMissionType: string
{
    case Attack = 'attack';

    case Colonization = 'colonization';

    case Stationing = 'stationing';

    case Transport = 'transport';
}
