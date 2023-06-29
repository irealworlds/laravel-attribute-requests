<?php

namespace Ireal\AttributeRequests\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:request-mapper')]
class RequestMapperMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:request-mapper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request property mapper class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'RequestPropertyMapper';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if (file_exists(base_path() . '/stubs/request-property-mapper.stub')) {
            return base_path() . '/stubs/request-property-mapper.stub';
        } else {
            return base_path() . '/vendor/irealworlds/laravel-attribute-requests/stubs/request-property-mapper.stub';
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\Requests\Mappers';
    }
}