<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\SharedKernel\DoctrineEntityTrait;
use App\SharedKernel\Exception\NonDoctrineGetterCallException;
use App\SharedKernel\Exception\NonDoctrineSetterCallException;

class TestedModel
{
    use DoctrineEntityTrait;

    private int $field = 30;

    public function getField(): int
    {
        return $this->field;
    }
}

final class ModelLockingTest extends IntegrationTestCase
{
    public function tearDown(): void
    {
        unset($_ENV[IntegrationTestCase::LOCKED_ENTITY_SETTERS]);
        unset($_ENV[IntegrationTestCase::LOCKED_ENTITY_GETTERS]);

        parent::tearDown();
    }

    function test_setting_private_property_with_locking_env_variable(): void
    {
        $this->expectException(NonDoctrineSetterCallException::class);

        $_ENV[IntegrationTestCase::LOCKED_ENTITY_SETTERS] = true;

        $model = new TestedModel();
        $model->field = 20;
    }

    function test_getting_private_property_with_locking_env_variable(): void
    {
        $this->expectException(NonDoctrineGetterCallException::class);

        $_ENV[IntegrationTestCase::LOCKED_ENTITY_GETTERS] = true;

        $model = new TestedModel();
        $value = $model->field;
    }

    function test_setting_private_property_with_unlocking_env_variable(): void
    {
        $_ENV[IntegrationTestCase::LOCKED_ENTITY_SETTERS] = false;

        $model = new TestedModel();
        $model->field = 20;

        $this->assertEquals(20, $model->getField());
    }

    function test_getting_private_property_with_unlocking_env_variable(): void
    {
        $_ENV[IntegrationTestCase::LOCKED_ENTITY_GETTERS] = false;

        $model = new TestedModel();
        $value = $model->field;

        $this->assertEquals(30, $value);
    }

    function test_setting_private_property_with_no_env_variable(): void
    {
        $this->expectException(NonDoctrineSetterCallException::class);

        $model = new TestedModel();
        $model->field = 20;

        $this->assertEquals(20, $model->getField());
    }

    function test_getting_private_property_with_no_env_variable(): void
    {
        $this->expectException(NonDoctrineGetterCallException::class);

        $model = new TestedModel();
        $value = $model->field;

        $this->assertEquals(30, $value);
    }
}