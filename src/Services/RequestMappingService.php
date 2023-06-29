<?php

namespace Ireal\AttributeRequests\Services;

use Illuminate\Contracts\Container\Container;
use Ireal\AttributeRequests\Attributes\RequestPropertyMapper;
use Ireal\AttributeRequests\Contracts\{IRequestMappingService, ITypeAnalysisService};
use Ireal\AttributeRequests\Mappers\{BackedEnumRequestPropertyMapper,
    BooleanRequestPropertyMapper,
    CarbonRequestPropertyMapper,
    CollectionRequestPropertyMapper,
    ComplexObjectRequestPropertyMapper,
    DateTimeRequestPropertyMapper,
    DirectRequestPropertyMapper,
    StandardObjectRequestPropertyMapper};
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;

readonly class RequestMappingService implements IRequestMappingService
{
    /**
     * RequestMappingService constructor method.
     *
     * @param Container $_container
     * @param ITypeAnalysisService $_typeService
     */
    public function __construct(
        private Container            $_container,
        private ITypeAnalysisService $_typeService
    ) {
    }

    /** @inheritDoc */
    public function mapRequestValue(mixed $input, mixed $mapper, ReflectionType|null $type): mixed {
        // If the mapper is a class string, instantiate it
        if (is_string($mapper)) {
            $mapper = $this->_container->make($mapper);
        }

        // Use the mapper instance to map the value
        return $mapper->map($input, $type);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function mapRequestValueForProperty(mixed $input, ReflectionProperty $property)
    {
        $mapper = $this->getMapperForProperty($property);
        return $this->mapRequestValue($input, $mapper, $property->getType());
    }

    /** @inheritDoc */
    public function getMapperForType(ReflectionType|null $type): string
    {
        if ($type instanceof ReflectionNamedType) {
            // Mapper for boolean properties
            if ($this->_typeService->isBooleanType($type)) {
                return BooleanRequestPropertyMapper::class;
            }

            // Mapper for Carbon properties
            if ($this->_typeService->isCarbonType($type)) {
                return CarbonRequestPropertyMapper::class;
            }

            // Mapper for collection properties
            if ($this->_typeService->isCollectionType($type)) {
                return CollectionRequestPropertyMapper::class;
            }

            // Map DateTime objects
            if ($this->_typeService->isDateType($type)) {
                return DateTimeRequestPropertyMapper::class;
            }

            // Map backed enums
            if ($this->_typeService->isBackedEnumType($type)) {
                return BackedEnumRequestPropertyMapper::class;
            }

            // Map objects
            if ($this->_typeService->isMappableObjectType($type)) {
                if ($type->isBuiltin()) {
                    return StandardObjectRequestPropertyMapper::class;
                } else {
                    return ComplexObjectRequestPropertyMapper::class;
                }
            }
        }

        // If no specific mapper can be inferred, use a direct mapping
        return DirectRequestPropertyMapper::class;
    }

    /** @inheritDoc */
    public function getMapperForProperty(ReflectionProperty $property): string
    {
        $attributes = $property->getAttributes(RequestPropertyMapper::class);

        if (!empty($attributes)) {
            /** @var RequestPropertyMapper $attribute */
            $attribute = $attributes[0]->newInstance();
            return $attribute->mapper;
        }

        return $this->getMapperForType($property->getType());
    }
}