# Laravel Attribute Requests
[![Tests](https://github.com/iRealWorlds/laravel-attribute-requests/actions/workflows/run_tests.yml/badge.svg)](https://github.com/iRealWorlds/laravel-attribute-requests/actions/workflows/run_tests.yml)
[![StyleCI](https://github.styleci.io/repos/660163282/shield?branch=master&style=plastic)](https://github.styleci.io/repos/660163282?branch=master&style=plastic)
![Packagist Version)](https://img.shields.io/packagist/v/irealworlds/laravel-attribute-requests)

## About
The Laravel Attribute Requests Package simplifies the process of handling and validating incoming requests by introducing the concept of Data Transfer Objects (DTOs). With this package, you can define request DTOs using attributes, making it easier to validate and process the input.

Instead of working directly with arrays and magic strings when consuming and validating requests, you can define dedicated DTO classes for each specific request, encapsulating all the required input fields and validation rules related to that request. This approach helps improve the maintainability, readability, and testability of your codebase, while also easing development by helping IDEs provide useful hints while you develop.

This way, you can leverage the power of PHP's type hinting to enforce the type of each attribute, preventing potential type-related errors.
Moreover, you can apply validation expressively, using PHP attributes with Laravel's built-in and custom validation rules.

## Installation
You can add this package through composer, using the require command:
```sh
composer require irealworlds/laravel-attribute-requests
```

If you want to modify the configuration file, you can first publish it using the `vendor:publish` command:
```sh
php artisan vendor:publish --tag=configuration --provider=AttributeRequestServiceProvider
```

## Example Usage
Defining requests is as simple as creating a class and decorating its properties for validation.
For example:
```php
use Ireal\AttributeRequests\Http\Request;

class PostStoreRequest extends Request
{
    public string $title;
    public string|null $body;
}
```
And then consuming it:
```php
public function store(PostStoreRequest $request)
{
    $post = Post::query()->create([
        'title' => $request->title, // IDE knows exactly what type of variable $request->title is here
        'body' => $request->body
    ]);
}
```

While the usage is similar to what you could do in Laravel by default, there are certain advantages to this approach.
For example:
* **Strong Typing:** Leverage the power of PHP's type hinting to enforce the type of each attribute, preventing potential type-related errors.
* **Automatic Validation:** The package automatically validates incoming requests based on the defined DTO rules, reducing the need for manual validation code. [More on validation](https://github.com/iRealWorlds/laravel-attribute-requests/wiki/Validation)
* **Compatibility:** The package is designed to work seamlessly with Laravel, leveraging its features and conventions.
* **IDE hinting:** Using this type of writing requests helps IDEs know what type of properties you are expecting, allowing them to help you when developing and enabling refactoring to be that much easier.

## Documentation
You can read the full documentation on the [package's wiki page](https://github.com/iRealWorlds/laravel-attribute-requests/wiki).
