<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Player\Bridge;

use TheGame\Application\SharedKernel\Domain\EntityId\PlayerIdInterface;

interface PlayerContextInterface
{
    public function getCurrentPlayerId(): PlayerIdInterface;
}
