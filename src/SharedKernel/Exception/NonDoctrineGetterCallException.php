<?php

declare(strict_types=1);

namespace App\SharedKernel\Exception;

use LogicException;

final class NonDoctrineGetterCallException extends LogicException
{
    private string $className;

    private string $propertyName;

    public function __construct(string $className, string $propertyName)
    {
        parent::__construct();
        $this->className = $className;
        $this->propertyName = $propertyName;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}