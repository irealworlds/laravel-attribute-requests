<?php

namespace Ireal\AttributeRequests\Providers;

use Illuminate\Support\ServiceProvider;
use Ireal\AttributeRequests\Contracts\{IRequestMappingService, ITypeAnalysisService};
use Ireal\AttributeRequests\Services\{RequestMappingService, TypeAnalysisService};

class AttributeRequestServiceProvider extends ServiceProvider
{
    /**
     * All the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        ITypeAnalysisService::class => TypeAnalysisService::class,
        IRequestMappingService::class => RequestMappingService::class,
    ];

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

    /**
     * @inheritDoc
     */
    public function provides(): array
    {
        return array_keys($this->bindings);
    }
}