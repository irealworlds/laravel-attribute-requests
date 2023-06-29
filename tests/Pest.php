<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Http\Request as IlluminateRequest;
use Ireal\AttributeRequests\Http\Request;
use Ireal\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getRequestDependencies(array $data = []): array
{
    app()->bind(IlluminateRequest::class, fn () => new IlluminateRequest($data));
    $reflection = new ReflectionClass(Request::class);
    $constructor = $reflection->getConstructor();
    if (!$constructor) {
        return [];
    }

    $dependencies = [];

    foreach ($constructor->getParameters() as $parameter) {
        $type = $parameter->getType()->getName();

        if ($type === IlluminateRequest::class) {
            $dependencies[] = new IlluminateRequest($data);
        } else {
            $dependencies[] = app()->make($type);
        }
    }

    return $dependencies;
}
