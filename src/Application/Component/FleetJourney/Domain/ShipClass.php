<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

enum ShipClass: string
{
    case Colonization = 'colonization';

    case Fighter = 'fighter';

    case Transporter = 'transporting';
}
