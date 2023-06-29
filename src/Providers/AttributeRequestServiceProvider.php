<?php

namespace Ireal\AttributeRequests\Providers;

use Illuminate\Support\ServiceProvider;

class AttributeRequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../../config/requests.php' => $this->app->configPath('requests.php'),
        ]);

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/requests.php',
            'requests'
        );
    }
}