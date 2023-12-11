<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain;

enum PlanetBiome: string
{
    case Desert = 'desert';

    case Jungle = 'jungle';

    case Lava = 'lava';

    case Magnetic = 'magnetic';

    case Watery = 'watery';
}
