<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Auth\GenericUser;
use SnakeInk\Crude\Concerns\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

abstract class RouteTestCase extends BaseTestCase
{
    use InteractsWithExceptionHandling;
    use InteractsWithDatabase;
    use CreatesApplication;

    protected GenericUser $genericUser;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->genericUser = new GenericUser(['id' => 1, 'name' => 'Surucucu']);
    }
}
