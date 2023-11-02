<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain;

use TheGame\Application\Component\BuildingConstruction\Domain\Exception\InvalidBuildingTypeException;

final class BuildingType
{
    private const SUPPORTED_TYPES = [
        'mine',
    ];

    public function __construct(
        private readonly string $type,
    ) {
        if (in_array($this->type, self::SUPPORTED_TYPES) === false) {
            throw new InvalidBuildingTypeException($this->type);
        }
    }

    public function getValue(): string
    {
        return $this->type;
    }
}
