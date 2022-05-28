<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Enum;

enum OperationType
{
    case AmountChange;

    case ChangeSpeed;
}