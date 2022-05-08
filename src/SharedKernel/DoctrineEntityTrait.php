<?php

declare(strict_types=1);

namespace App\SharedKernel;

use App\SharedKernel\Exception\NonDoctrineGetterCallException;
use App\SharedKernel\Exception\NonDoctrineSetterCallException;
use Doctrine\Common\Collections\Collection;
use Tests\Integration\IntegrationTestCase;

trait DoctrineEntityTrait
{
    protected ?int $id;

    public function __set($property, $value): void
    {
        if ($this->isLocked(IntegrationTestCase::LOCKED_ENTITY_SETTERS)) {
            throw new NonDoctrineSetterCallException(static::class, $property);
        }

        $reflection = new \ReflectionProperty(self::class, $property);

        $isCollectionProperty = $reflection->getType()->getName() === Collection::class;
        if (is_array($value) && $isCollectionProperty) {
            foreach ($value as $row) {
                $this->{$property}->add($row);
            }

            return;
        }

        $this->{$property} = $value;
    }

    public function __get($property): mixed
    {
        if ($this->isLocked(IntegrationTestCase::LOCKED_ENTITY_GETTERS)) {
            throw new NonDoctrineGetterCallException(static::class, $property);
        }

        return $this->{$property};
    }

    private function isLocked(string $lockCode): bool
    {
        return !isset($_ENV[$lockCode]) || $_ENV[$lockCode] === true;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
