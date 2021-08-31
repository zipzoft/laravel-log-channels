<?php

namespace Zipzoft\LogChannels;

use Illuminate\Support\ServiceProvider;
use Zipzoft\LogChannels\Commands;

class LogChannelServiceProvider extends ServiceProvider
{

    /**
     * @var string[]
     */
    public const LOGGING_DRIVERS = [
        'cloudwatch',
        'elasticsearch',
    ];


    public function register()
    {
        foreach (static::LOGGING_DRIVERS as $name) {
            $this->mergeConfigFrom(__DIR__."/../config/{$name}.php", "logging.channels.{$name}");
        }
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
            ]);
        }
    }
}
