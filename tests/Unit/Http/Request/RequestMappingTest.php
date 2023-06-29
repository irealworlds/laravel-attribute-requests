<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpUnused */

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Ireal\AttributeRequests\Http\Request;
use Ireal\Tests\Fakes\ComplexNumber;
use Ireal\Tests\Fakes\Enums\Color;
use Ireal\Tests\Fakes\Enums\DayOfTheWeek;
use Ireal\Tests\Fakes\NestedObject;
use function Pest\Faker\fake;

it('should map null to nullable values', function (array $data): void {
    // Arrange
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public int|null $nullProperty;
    };

    // Assert
    expect($request->nullProperty)
        ->toBeNull();
})->with([
    'explicitly null' => [['nullProperty' => null]],
    'not present' => [[]]
]);

it('should map scalar types', function (): void {
    // Arrange
    $data = [
        'intProperty1' => 124,
        'intProperty2' => '241',

        'floatProperty1' => 123.45,
        'floatProperty2' => '954.12',
        'floatProperty3' => '954',
        'floatProperty4' => 1855,

        'stringProperty1' => 'test string 1',

        'booleanProperty1' => true,
        'booleanProperty2' => false,
        'booleanProperty3' => 1,
        'booleanProperty4' => 0,
        'booleanProperty5' => "1",
        'booleanProperty6' => "0",
    ];
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public int $intProperty1;
        public int $intProperty2;

        public float $floatProperty1;
        public float $floatProperty2;
        public float $floatProperty3;
        public float $floatProperty4;

        public string $stringProperty1;

        public bool $booleanProperty1;
        public bool $booleanProperty2;
        public bool $booleanProperty3;
        public bool $booleanProperty4;
        public bool $booleanProperty5;
        public bool $booleanProperty6;
    };

    // Assert
    expect($request->intProperty1)
        ->toEqual(124);
    expect($request->intProperty2)
        ->toEqual(241);

    expect($request->floatProperty1)
        ->toEqual(123.45);
    expect($request->floatProperty2)
        ->toEqual(954.12);
    expect($request->floatProperty3)
        ->toEqual(954);
    expect($request->floatProperty4)
        ->toEqual(1855);

    expect($request->stringProperty1)
        ->toEqual('test string 1');

    expect($request->booleanProperty1)
        ->toBeTrue();
    expect($request->booleanProperty2)
        ->toBeFalse();
    expect($request->booleanProperty3)
        ->toBeTrue();
    expect($request->booleanProperty4)
        ->toBeFalse();
    expect($request->booleanProperty5)
        ->toBeTrue();
    expect($request->booleanProperty6)
        ->toBeFalse();
});

it('should map iterable types', function (): void {
    // Arrange
    $data = [
        'collectionProperty1' => $this->faker->words(7),
        'collectionProperty2' => $this->faker->words(7),
        'collectionProperty3' => $this->faker->words(7),

        'iterableProperty1' => $this->faker->words(7),
        'iterableProperty2' => $this->faker->words(7),
    ];
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public Collection $collectionProperty1;
        public Enumerable $collectionProperty2;
        public ArrayAccess $collectionProperty3;

        public array $iterableProperty1;
        public iterable $iterableProperty2;
    };

    // Assert
    expect($request->collectionProperty1)
        ->toBeIterable()
        ->toEqual(new Collection($data['collectionProperty1']));
    expect($request->collectionProperty2)
        ->toBeIterable()
        ->toEqual(new Collection($data['collectionProperty2']));
    expect($request->collectionProperty3)
        ->toBeIterable()
        ->toEqual(new Collection($data['collectionProperty3']));

    expect($request->iterableProperty1)
        ->toEqual($data['iterableProperty1']);
    expect($request->iterableProperty2)
        ->toEqual($data['iterableProperty2']);
});

it('should map date types', function (): void {
    // Arrange
    $data = [
        'dateProperty1' => $this->faker->date(),
        'dateProperty2' => $this->faker->date(),
        'dateProperty3' => $this->faker->date(),

        'dateTimeProperty1' => Carbon::parse($this->faker->dateTime())->toIso8601String(),
        'dateTimeProperty2' => Carbon::parse($this->faker->dateTime())->toIso8601String(),
        'dateTimeProperty3' => Carbon::parse($this->faker->dateTime())->toIso8601String(),
    ];
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public Carbon $dateProperty1;
        public DateTimeInterface $dateProperty2;
        public DateTime $dateProperty3;

        public Carbon $dateTimeProperty1;
        public DateTimeInterface $dateTimeProperty2;
        public DateTime $dateTimeProperty3;
    };

    // Assert
    expect($request->dateProperty1)
        ->toEqual(Carbon::parse($data['dateProperty1']));
    expect($request->dateProperty2)
        ->toEqual(Carbon::parse($data['dateProperty2'])->toDateTime());
    expect($request->dateProperty3)
        ->toEqual(Carbon::parse($data['dateProperty3'])->toDateTime());

    expect($request->dateTimeProperty1)
        ->toEqual(Carbon::parse($data['dateTimeProperty1']));
    expect($request->dateTimeProperty2)
        ->toEqual(Carbon::parse($data['dateTimeProperty2'])->toDateTime());
    expect($request->dateTimeProperty3)
        ->toEqual(Carbon::parse($data['dateTimeProperty3'])->toDateTime());
});

it('should map backed enum types', function (): void {
    // Arrange
    $data = [
        'stringBackedEnum' => $this->faker->randomElement(Color::cases())->value,
        'intBackedEnum' => $this->faker->randomElement(DayOfTheWeek::cases())->value,
    ];
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public Color $stringBackedEnum;
        public DayOfTheWeek $intBackedEnum;
    };

    // Assert
    expect($request->stringBackedEnum)
        ->toEqual(Color::from($data['stringBackedEnum']));
    expect($request->intBackedEnum)
        ->toEqual(DayOfTheWeek::from($data['intBackedEnum']));
});

it('should map standard objects', function (): void {
    // Arrange
    $data = [
        'simpleObjectProperty' => [
            'a' => 'b',
            'c' => 'd'
        ]
    ];
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public object $simpleObjectProperty;
    };

    // Assert
    expect($request->simpleObjectProperty)
        ->toEqualCanonicalizing((object) $data['simpleObjectProperty']);
});

it('should map class defined object', function (): void {
    // Arrange
    $object = new ComplexNumber();
    $object->real = fake()->randomFloat();
    $object->imaginary = fake()->randomFloat();
    $data = [
        'complexNumber' => json_decode(
            json_encode($object),
            true
        )
    ];
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public ComplexNumber $complexNumber;
    };

    // Assert
    expect($request->complexNumber)
        ->toEqualCanonicalizing($object);
});

it('should map nested objects', function (): void {
    // Arrange
    $object = new NestedObject();
    $object->child = new NestedObject();
    $object->child->child = new NestedObject();
    $object->child->child->child = null;
    $data = [
        'object' => json_decode(
            json_encode($object),
            true
        )
    ];
    $baseRequest = new BaseRequest($data);
    $validationFactory = app()->make(ValidationFactory::class);
    $configRepository = app()->make(ConfigRepository::class);

    // Act
    $request = new class ($baseRequest, $validationFactory, $configRepository) extends Request {
        public NestedObject $object;
    };

    // Assert
    expect($request->object)
        ->toEqualCanonicalizing($object);
});