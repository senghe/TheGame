<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\ValueObject;

use TheGame\Application\Component\Shipyard\Domain\ConstructibleInterface;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;

final class Cannon extends AbstractConstructible implements ConstructibleInterface
{
    public function getConstructionUnit(): ConstructibleUnit
    {
        return ConstructibleUnit::Cannon;
    }
}
