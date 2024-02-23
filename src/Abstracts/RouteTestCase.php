<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use SnakeInk\Crude\Concerns\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

abstract class RouteTestCase extends BaseTestCase
{
    use InteractsWithExceptionHandling;
    use InteractsWithDatabase;
    use CreatesApplication;

    protected Authenticatable $defaultUserActor;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultUserActor = $this->generateDefaultUserActor();
    }

    protected function generateDefaultUserActor(): Authenticatable
    {
        return new GenericUser(['id' => 1, 'name' => 'Surucucu']);
    }
}
