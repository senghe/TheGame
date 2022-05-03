<?php

declare(strict_types=1);

namespace Tests\Integration;

use ApiTestCase\JsonApiTestCase;

abstract class IntegrationTestCase extends JsonApiTestCase
{
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->dataFixturesPath = __DIR__ . '/Fixtures/Doctrine';
    }
}
