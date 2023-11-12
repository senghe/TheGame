<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Event;

use InvalidArgumentException;
use TheGame\Application\SharedKernel\EventInterface;

final class NewCannonsHaveBeenQueuedEvent implements EventInterface
{
    /** @phpstan-ignore-next-line The validation is done in constructor */
    public function __construct(
        private readonly string $type,
        private readonly int $quantity,
        private readonly string $planetId,
        private readonly array $resourceRequirements,
    ) {
        foreach ($this->resourceRequirements as $key => $value) {
            if (is_string($key) === false || is_int($value) === false) {
                throw new InvalidArgumentException('Invalid resource requirements key or value');
            }
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    /** @return array<string, int> */
    public function getResourceRequirements(): array
    {
        return $this->resourceRequirements;
    }
}
