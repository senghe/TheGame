<?php

declare(strict_types=1);

namespace App\Domain\Planet\Exception;

use App\Domain\Planet\Entity\BuildingInterface;
use App\Domain\Planet\Entity\PlanetInterface;

final class PlaceRequirementsNotMetException extends BuildingRequirementsNotMetException
{
    private BuildingInterface $building;

    private PlanetInterface $planet;

    public function __construct(
        BuildingInterface $building,
        PlanetInterface $planet
    ) {
        parent::__construct();

        $this->building = $building;
        $this->planet = $planet;
    }

    public function getBuilding(): BuildingInterface
    {
        return $this->building;
    }

    public function getPlanet(): PlanetInterface
    {
        return $this->planet;
    }
}