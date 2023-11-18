<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

enum MissionType: string
{
    case Stationing = 'stationing';

    case Transport = 'transport';

    case Attack = 'attack';

    case FlyBack = 'fly-back';
}
