<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpUnused */

use Illuminate\Validation\Rules\In;
use Ireal\AttributeRequests\Attributes\ValidateRule;
use Ireal\AttributeRequests\Http\Request;
use Ireal\Tests\Fakes\Enums\{Color, DayOfTheWeek};

it('should add rules from validation attributes', function (): void {
    // Arrange
    $data = [
        'stringBackedEnum' => $this->faker->randomElement(Color::cases())->value,
        'intBackedEnum' => $this->faker->randomElement(DayOfTheWeek::cases())->value,
    ];

    $request = new class (...getRequestDependencies($data)) extends Request {
        #[ValidateRule(new In([1, 2, 3, 4]))]
        public mixed $property1;

        #[ValidateRule('min:0')]
        #[ValidateRule('required')]
        public mixed $property2;

        /** @inheritDoc */
        public function validateResolved()
        {
        }
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKey('property1')
        ->and($rules['property1'])
        ->toContain((string) (new In([1, 2, 3, 4])));

    expect($rules)
        ->toHaveKey('property2')
        ->and($rules['property2'])
        ->toContain('required', 'min:0');
});