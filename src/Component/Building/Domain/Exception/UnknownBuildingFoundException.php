<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Exception;

use App\Component\Building\Domain\Entity\BuildingInterface;
use LogicException;

final class UnknownBuildingFoundException extends LogicException
{
    private BuildingInterface $building;

    public function __construct(BuildingInterface $building)
    {
        parent::__construct();

        $this->building = $building;
    }

    public function getBuilding(): BuildingInterface
    {
        return $this->building;
    }
}