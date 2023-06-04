<?php

namespace App\Providers;

use Dotenv\Dotenv;

class EnvironmentVariablesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $env =  Dotenv::createImmutable();
        $env->load();
    }
};
