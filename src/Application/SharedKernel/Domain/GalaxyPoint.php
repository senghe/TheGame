<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

use InvalidArgumentException;

final class GalaxyPoint implements GalaxyPointInterface
{
    public function __construct(
        private readonly int $galaxy,
        private readonly int $solarSystem,
        private readonly int $planet,
    ) {
    }

    public static function fromString(string $value): self
    {
        $withoutBrackets = substr($value, 1, strlen($value) - 2);
        $exploded = explode(':', $withoutBrackets);
        if (count($exploded) != 3) {
            throw new InvalidArgumentException(sprintf('Cannot parse galaxy point %s', $value));
        }

        return new self(...$exploded);
    }

    public function getGalaxy(): int
    {
        return $this->galaxy;
    }

    public function getSolarSystem(): int
    {
        return $this->solarSystem;
    }

    public function getPlanet(): int
    {
        return $this->planet;
    }

    public function format(): string
    {
        return sprintf(
            '[%d:%d:%d]',
            $this->galaxy,
            $this->solarSystem,
            $this->planet
        );
    }

    /** @return int[] */
    public function toArray(): array
    {
        return [
            $this->galaxy,
            $this->solarSystem,
            $this->planet,
        ];
    }
}
