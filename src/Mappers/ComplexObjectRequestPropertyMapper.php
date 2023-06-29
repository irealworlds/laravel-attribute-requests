<?php

namespace Ireal\AttributeRequests\Mappers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Ireal\AttributeRequests\Contracts\IRequestMappingService;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionClass;
use ReflectionProperty;

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
     * @throws \ReflectionException
     * @throws BindingResolutionException
     */
    public function map(mixed $input, \ReflectionNamedType $type): object
    {
        $class = new ReflectionClass($type->getName());
        $instance = $class->newInstance();
        $input = (array)$input;
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