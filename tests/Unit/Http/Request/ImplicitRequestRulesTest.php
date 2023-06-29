<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpUnused */

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;
use Ireal\AttributeRequests\Http\Request;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Ireal\Tests\Fakes\{ComplexNumber, Enums\Color, NestedObject};

it('should infer required or nullable from type', function () {
    // Arrange
    $data = [
        'requiredProperty' => 123,
        'nullableProperty' => null
    ];
    $request = new class (...getRequestDependencies($data)) extends Request {
        public int $requiredProperty;
        public int|null $nullableProperty;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKey('requiredProperty')
        ->and($rules['requiredProperty'])
        ->toContain('required');

    expect($rules)
        ->toHaveKey('nullableProperty')
        ->and($rules['nullableProperty'])
        ->toContain('nullable');
});

it('should infer rules for scalar properties', function () {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public ?bool $booleanProperty;

        public ?string $stringProperty;

        public ?int $numericProperty1;
        public ?float $numericProperty2;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKey('booleanProperty')
        ->and($rules['booleanProperty'])
        ->toContain('boolean');

    expect($rules)
        ->toHaveKey('stringProperty')
        ->and($rules['stringProperty'])
        ->toContain('string');

    expect($rules)
        ->toHaveKeys([
            'numericProperty1',
            'numericProperty2',
        ]);
    expect($rules['numericProperty1'])
        ->toContain('numeric');
    expect($rules['numericProperty2'])
        ->toContain('numeric');
});

it('should infer rules for file properties', function (): void {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public ?SplFileInfo $fileProperty1;
        public ?UploadedFile $fileProperty2;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKeys([
            'fileProperty1',
            'fileProperty2',
        ]);
    expect($rules['fileProperty1'])
        ->toEqual(['nullable', 'file']);
    expect($rules['fileProperty2'])
        ->toEqual(['nullable', 'file']);
});

it('should infer rules for date properties', function (): void {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public ?DateTimeInterface $dateProperty1;
        public ?DateTime $dateProperty2;
        public ?Carbon $dateProperty3;
        public ?\Illuminate\Support\Carbon $dateProperty4;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKeys([
            'dateProperty1',
            'dateProperty2',
            'dateProperty3',
            'dateProperty4',
        ]);
    expect($rules['dateProperty1'])
        ->toEqual(['nullable', 'date']);
    expect($rules['dateProperty2'])
        ->toEqual(['nullable', 'date']);
    expect($rules['dateProperty3'])
        ->toEqual(['nullable', 'date']);
    expect($rules['dateProperty4'])
        ->toEqual(['nullable', 'date']);
});

it('should infer rules for iterable properties', function (): void {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public ?array $iterableProperty1;
        public ?iterable $iterableProperty2;
        public ?Collection $iterableProperty3;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKeys([
            'iterableProperty1',
            'iterableProperty2',
            'iterableProperty3',
        ]);
    expect($rules['iterableProperty1'])
        ->toEqual(['nullable', 'array']);
    expect($rules['iterableProperty2'])
        ->toEqual(['nullable', 'array']);
    expect($rules['iterableProperty3'])
        ->toEqual(['nullable', 'array']);
});

it('should infer rules for standard objects', function (): void {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public ?object $objectProperty1;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKey('objectProperty1')
        ->and($rules['objectProperty1'])
        ->toContain('array');
});

it('should infer rules for class objects', function (): void {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public ?ComplexNumber $objectProperty2;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKeys([
            'objectProperty2',
            'objectProperty2.real',
            'objectProperty2.imaginary',
        ]);
    expect($rules['objectProperty2'])
        ->toContain('array');
    expect($rules['objectProperty2.real'])
        ->toContain('numeric');
    expect($rules['objectProperty2.imaginary'])
        ->toContain('numeric');
});

it('should infer rules for nested class objects up to the configured max depth', function (int $depth): void {
    // Arrange
    /** @var ConfigRepository $config */
    $config = app()->make(ConfigRepository::class);
    $config->set('requests.nested_validation_depth', $depth);
    $data = [
        'object' => []
    ];
    $request = new class (...getRequestDependencies($data)) extends Request {
        public NestedObject $object;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKey('object');
    expect($rules['object'])
        ->toEqual(['required', 'array']);

    $field = 'object.child';
    for ($i = 1; $i < $depth; ++$i) {
        expect($rules)
            ->toHaveKey($field);
        expect($rules[$field])
            ->toEqual(['nullable', 'array']);

        $field .= '.child';
    }

})->with([1, 10, 20, 100]);

it('should infer rules for backed enums', function (): void {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public ?Color $backedEnumProperty1;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->toHaveKey('backedEnumProperty1')
        ->and((new Collection($rules['backedEnumProperty1']))
            ->some(fn(mixed $rule): bool =>
                $rule instanceof Enum &&
                serialize($rule) === serialize(new Enum(Color::class))
            ))
        ->toBeTrue()
        ->and($rules['backedEnumProperty1'])
        ->not()->toContain('array');
});

it('should not infer rules from untyped properties', function (): void {
    // Arrange
    $request = new class (...getRequestDependencies()) extends Request {
        public $untypedProperty;
    };

    // Act
    $rules = $request->rules();

    // Assert
    expect($rules)
        ->not()->toHaveKey('untypedProperty');
});