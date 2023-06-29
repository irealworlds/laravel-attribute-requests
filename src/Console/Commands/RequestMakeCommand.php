<?php

namespace Ireal\AttributeRequests\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand as BaseMakeCommand;

class RequestMakeCommand extends BaseMakeCommand
{
    /** @inheritDoc */
    protected function getStub(): string
    {
        if (file_exists(base_path() . '/stubs/request.stub')) {
            return base_path() . '/stubs/request.stub';
        } else {
            return base_path() . '/vendor/irealworlds/laravel-attribute-requests/stubs/request.stub';
        }
    }
}