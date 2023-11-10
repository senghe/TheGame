<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

use TheGame\Application\SharedKernel\Domain\Exception\InvalidBuildingTypeException;

enum BuildingType: string
{
    case ResourceMine = 'mine';

    case ResourceStorage = 'resource-storage';

    case Shipyard = 'shipyard';

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $type) {
            if ($name === $type->name) {
                return $type;
            }
        }

        throw new InvalidBuildingTypeException($name);
    }
};
