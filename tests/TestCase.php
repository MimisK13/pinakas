<?php

namespace Mimisk\Pinakas\Tests;

use Mimisk\Pinakas\PinakasServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PinakasServiceProvider::class,
        ];
    }
}
