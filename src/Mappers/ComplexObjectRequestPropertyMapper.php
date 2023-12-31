<?php

namespace Ireal\AttributeRequests\Mappers;

use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;
use Ireal\AttributeRequests\Contracts\{IRequestMappingService, IRequestPropertyMapper};
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<object>
 */
readonly class ComplexObjectRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * ComplexObjectRequestPropertyMapper constructor method.
     *
     * @param IRequestMappingService $_requestMappingService
     */
    public function __construct(
        private IRequestMappingService $_requestMappingService
    ) {
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function map(mixed $input, ReflectionType|null $type): object
    {
        if (!($type instanceof ReflectionNamedType)) {
            throw new InvalidArgumentException("The given property does not have a named type.");
        }

        $class = new ReflectionClass($type->getName());
        $instance = $class->newInstance();
        $input = (array) $input;
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $key = $property->getName();
            if (isset($input[$key])) {
                $mappedValue = $this->_requestMappingService->mapRequestValue(
                    $input[$key],
                    $this->_requestMappingService->getMapperForProperty($property),
                    $property->getType()
                );
                $instance->{$key} = $mappedValue;
            } else if ($property->getType()->allowsNull()) {
                $instance->{$key} = null;
            }
        }
        return $instance;
    }
}