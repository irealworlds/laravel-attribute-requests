<?php

namespace Ireal\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Ireal\AttributeRequests\Providers\AttributeRequestServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use WithFaker;

    /**
     * Props that should be used in tests.
     *
     * @var Collection
     */
    protected Collection $props;

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->setBasePath(__DIR__ . '/../');
        $this->setUpFaker();
        $this->props = new Collection();
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array
    {
        return [AttributeRequestServiceProvider::class];
    }
}
