<?php

declare(strict_types=1);

namespace App\Entity;

class ResourceStore implements ResourceStoreInterface
{
    protected int $id;

    protected ?int $amount = 0;

    protected ?int $increaseSpeed = 0;

    protected ?int $maxAmount;

    protected \DateTimeInterface $updatedAt;

    protected ResourceInterface $resource;

    public function __set($property, $value): void
    {
        $this->{$property} = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        $timeInterval = (new \DateTime())->getTimestamp() - $this->updatedAt->getTimestamp();
        $step = $this->increaseSpeed / 3600;
        return (int) floor($this->amount + $step * $timeInterval);
    }

    public function isFull(): bool
    {
        return $this->getAmount() >= (int) $this->maxAmount;
    }
}