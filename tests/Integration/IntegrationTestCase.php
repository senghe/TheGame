<?php

declare(strict_types=1);

namespace Tests\Integration;

use ApiTestCase\JsonApiTestCase;

abstract class IntegrationTestCase extends JsonApiTestCase
{
    public const LOCKED_ENTITY_SETTERS = 'LOCKED_ENTITY_SETTERS';

    public const LOCKED_ENTITY_GETTERS = 'LOCKED_ENTITY_GETTERS';

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->dataFixturesPath = __DIR__ . '/Fixtures/Doctrine';
    }

    protected function loadFixturesFromFile(string $source): array
    {
        $_ENV[self::LOCKED_ENTITY_SETTERS] = false;
        $fixtures = parent::loadFixturesFromFile($source);
        $_ENV[self::LOCKED_ENTITY_SETTERS] = true;

        return $fixtures;
    }

    protected function act(callable $callback): void
    {
        $_ENV[self::LOCKED_ENTITY_GETTERS] = true;
        $callback();
        $_ENV[self::LOCKED_ENTITY_GETTERS] = false;
    }
}
