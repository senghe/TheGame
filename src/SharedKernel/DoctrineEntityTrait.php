<?php

declare(strict_types=1);

namespace App\SharedKernel;

use Doctrine\Common\Collections\Collection;

trait DoctrineEntityTrait
{
    protected ?int $id;

    public function __set($property, $value): void
    {
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

    public function getId(): int
    {
        return $this->id;
    }
}
