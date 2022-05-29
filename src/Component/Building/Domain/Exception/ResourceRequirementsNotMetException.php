<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Exception;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\SharedKernel\Domain\Entity\PlanetInterface;

final class ResourceRequirementsNotMetException extends BuildingRequirementsNotMetException
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