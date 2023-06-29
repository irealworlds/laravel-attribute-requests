<?php

namespace Ireal\AttributeRequests\Http;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\{Factory as ValidationFactory, ValidatesWhenResolved, Validator};
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\{Carbon, Collection, Stringable};
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Ireal\AttributeRequests\Attributes\ValidateRule;
use Ireal\AttributeRequests\Contracts\IRequestMappingService;
use Ireal\AttributeRequests\Contracts\ITypeAnalysisService;
use Ireal\AttributeRequests\Validation\ValidationRuleSet;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Request extends BaseRequest implements ValidatesWhenResolved
{
    use ValidatesWhenResolvedTrait;

    /**
     * @param BaseRequest            $request
     * @param ValidationFactory      $_validationFactory
     * @param ConfigRepository       $_configuration
     * @param IRequestMappingService $_mappingService
     * @param ITypeAnalysisService   $_typeService
     *
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function __construct(
        BaseRequest $request,
        private readonly ValidationFactory $_validationFactory,
        private readonly ConfigRepository $_configuration,
        private readonly IRequestMappingService $_mappingService,
        private readonly ITypeAnalysisService $_typeService
    ) {
        // Pass the values from the request to the parent constructor
        parent::__construct(
            query: $request->query->all(),
            request: $request->request->all(),
            attributes: $request->attributes->all(),
            cookies: $request->cookies->all(),
            files: $request->files->all(),
            server: $request->server->all(),
            content: $request->content,
        );

        $properties = $this->_getBodyProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $requestName = $this->_mappingService->getRequestNameForProperty($property);
            if ($request->has($requestName)) {
                // Get the value from the request
                $value = $request->get($requestName) ?? $request->file($requestName);

                // Cast the value to the correct type
                $value = $this->_mappingService->mapRequestValueForProperty($value, $property);

                $this->{$propertyName} = $value;
                if (is_scalar($value)) {
                    $this->request->set($propertyName, $this->{$propertyName});
                }
            } else {
                $this->{$propertyName} = null;
            }
        }
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return parent::get($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function input($key = null, $default = null)
    {
        return parent::input($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function str($key, $default = null): Stringable
    {
        return parent::str($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function string($key, $default = null): Stringable
    {
        return parent::string($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function boolean($key = null, $default = false): bool
    {
        return parent::boolean($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function integer($key, $default = 0): int
    {
        return parent::integer($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function float($key, $default = 0.0): float
    {
        return parent::float($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function date($key, $format = null, $tz = null): ?Carbon
    {
        return parent::date($key, $format, $tz);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function enum($key, $enumClass)
    {
        return parent::enum($key, $enumClass);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function collect($key = null): Collection
    {
        return parent::collect($key);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function file($key = null, $default = null): array|UploadedFile|null
    {
        return parent::file($key, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function filled($key): bool
    {
        return parent::filled($key);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function whenFilled($key, callable $callback, callable $default = null)
    {
        return parent::whenFilled($key, $callback, $default);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function anyFilled($keys): bool
    {
        return parent::anyFilled($keys);
    }

    /**
     * @inheritDoc
     *
     * @deprecated Use class properties instead.
     */
    public function isNotFilled($key): bool
    {
        return parent::isNotFilled($key);
    }

    /**
     * Create the default validator instance.
     *
     * @throws ReflectionException
     *
     * @return Validator
     */
    protected function validator(): Validator
    {
        $rules = $this->rules();

        $validator = $this->_validationFactory->make(
            data: $this->all(),
            rules: $rules,
        );

        if ($this->isPrecognitive()) {
            $validator->setRules(
                $this->filterPrecognitiveRules($validator->getRulesWithoutPlaceholders())
            );
        }

        return $validator;
    }

    /**
     * Get the validation rules for this request.
     *
     * @throws ReflectionException
     *
     * @return array
     */
    public function rules(): array
    {
        $properties = $this->_getBodyProperties();
        $rules = new ValidationRuleSet();

        foreach ($properties as $property) {
            $this->_setRulesForProperty($property, $rules);
        }

        return $rules->toArray();
    }

    /**
     * Add validation rules that should be applied to the given {@link $property} to the rule set.
     *
     * @param ReflectionProperty $property
     * @param ValidationRuleSet  $rules
     * @param string|null        $field        The name of the field to add to the rule set. If null, the property name will be used.
     * @param int                $currentDepth
     *
     * @throws ReflectionException
     *
     * @return void
     */
    private function _setRulesForProperty(ReflectionProperty $property, ValidationRuleSet $rules, ?string $field = null, int $currentDepth = 0): void
    {
        $this->_setImplicitRulesForProperty($property, $rules, $field, $currentDepth);
        $this->_setExplicitRulesForProperty($property, $rules, $field);
    }

    /**
     * Add the implicit validation rules that should be applied to the given {@link $property} to the rule set.
     *
     * @param ReflectionProperty $property
     * @param ValidationRuleSet  $rules
     * @param string|null        $field        The name of the field to add to the rule set. If null, the property name will be used.
     * @param int                $currentDepth
     *
     * @throws ReflectionException
     *
     * @return void
     */
    private function _setImplicitRulesForProperty(ReflectionProperty $property, ValidationRuleSet $rules, ?string $field = null, int $currentDepth = 0): void
    {
        $field = $field ?? $property->getName();

        // Add rules inferred from the property type
        if ($property->hasType()) {
            $type = $property->getType();

            // If the property is non-nullable, then add the required rule
            if ($type->allowsNull()) { // TODO if this is part of an object that is nullable in the parent, also allow null.
                $rules->addRule($field, 'nullable');
            } else {
                $rules->addRule($field, 'required');
            }

            // If the type provides useful info, add validators
            if ($type->getName() === 'bool') {
                $rules->addRule($field, 'boolean');
            } elseif ($type->getName() === 'string') {
                $rules->addRule($field, 'string');
            } elseif ($this->_typeService->isDateType($type)) {
                $rules->addRule($field, 'date');
            } elseif ($this->_typeService->isFileType($type)) {
                $rules->addRule($field, 'file');
            } elseif ($this->_typeService->isNumericType($type)) {
                $rules->addRule($field, 'numeric');
            } elseif ($this->_typeService->isIterableType($type)) {
                $rules->addRule($field, 'array');
            } elseif ($this->_typeService->isBackedEnumType($type)) {
                $rules->addRule($field, new Enum($type->getName()));
            } elseif ($this->_typeService->isMappableObjectType($type)) {
                $rules->addRule($field, 'array');

                if ($currentDepth <= $this->_configuration->get('requests.nested_validation_depth')) {
                    if (!$type->isBuiltin()) {
                        $class = new ReflectionClass($type->getName());
                        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
                        foreach ($properties as $property) {
                            $this->_setRulesForProperty(
                                $property,
                                $rules,
                                $field.'.'.$property->getName(),
                                $currentDepth + 1
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * Add the explicit validation rules that should be applied to the given {@link $property} to the rule set.
     *
     * @param ReflectionProperty $property
     * @param ValidationRuleSet  $rules
     * @param string|null        $field    The name of the field to add to the rule set. If null, the property name will be used.
     *
     * @return void
     */
    private function _setExplicitRulesForProperty(ReflectionProperty $property, ValidationRuleSet $rules, ?string $field = null): void
    {
        /** @var Collection<ValidateRule> $attributes */
        $attributes = (new Collection($property->getAttributes()))
            ->filter(function (ReflectionAttribute $attribute) {
                if ($attribute->getName() === ValidateRule::class) {
                    return true;
                }

                $class = new ReflectionClass($attribute->getName());

                return $class->isSubclassOf(ValidateRule::class);
            })
            ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());

        $field = $field ?? $property->getName();

        foreach ($attributes as $attribute) {
            $rules->addRule($field, $attribute->rule);
        }
    }

    /**
     * Get the properties that should be mapped to the request body.
     *
     * @throws ReflectionException
     *
     * @return ReflectionProperty[]
     */
    private function _getBodyProperties(): array
    {
        $reflection = new ReflectionClass(static::class);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        // Only properties that are not defined in this class or its parents should be taken into account
        // By just excluding properties from here and parents and not explicitly only including from static
        // allows for inheritance and composition.
        return array_filter($properties, function (ReflectionProperty $property) {
            if ($property->class === Request::class) {
                return false;
            } else {
                $class = new ReflectionClass($property->class);
                $maxChild = new ReflectionClass(Request::class);

                if ($class->isInterface()) {
                    return !$maxChild->implementsInterface($class);
                } else {
                    return !$maxChild->isSubclassOf($class);
                }
            }
        });
    }
}
