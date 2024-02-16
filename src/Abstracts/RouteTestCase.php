<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SnakeInk\Crude\Concerns\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

abstract class RouteTestCase extends BaseTestCase
{
    use InteractsWithExceptionHandling;
    use InteractsWithDatabase;
    use CreatesApplication;

    protected GenericUser|Authenticatable|Model $defaultUserActor;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultUserActor = $this->generateDefaultUserActor();
    }

    protected function generateDefaultUserActor(): GenericUser|Authenticatable|Model
    {
        return new GenericUser(['id' => 1, 'name' => 'Surucucu']);
    }
}
