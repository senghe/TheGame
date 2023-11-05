<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

interface ResourceStoragesContextInterface
{
    public function getLimit(int $level, ResourceIdInterface $resourceId): int;
}
