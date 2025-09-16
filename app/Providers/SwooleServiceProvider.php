<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SwooleServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\SwooleAutostartCommand::class,
            ]);
        }
    }

    // ...
}
