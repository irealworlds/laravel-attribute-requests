<?php

namespace Ireal\AttributeRequests\Contracts;

use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

interface IRequestMappingService
{
    /**
     * Map the given {@link $input} using a {@link mapper}.
     *
     * @template TOutput
     * @template TMapper of IRequestPropertyMapper<TOutput>
     * @param mixed $input
     * @param class-string<TMapper<TOutput>>|TMapper<TOutput> $mapper
     * @param ReflectionNamedType $type
     * @return TOutput
     * @throws BindingResolutionException
     */
    public function mapRequestValue(mixed $input, mixed $mapper, ReflectionNamedType $type): mixed;

    /**
     * Map the given {@link $input} using a mapper extracted from the given {@link $property}.
     *
     * @template TOutput
     * @param mixed $input
     * @param ReflectionProperty $property
     * @return TOutput
     * @throws BindingResolutionException
     */
    public function mapRequestValueForProperty(mixed $input, ReflectionProperty $property);

    /**
     * Get the mapper that should be applied for a property of the given {@link $type}.
     *
     * @param ReflectionNamedType $type
     * @return class-string<IRequestPropertyMapper>
     * @throws ReflectionException
     */
    public function getMapperForType(ReflectionNamedType $type): string;

    /**
     * Get the mapper that should be applied for the given {@link $property}.
     *
     * @param ReflectionProperty $property
     * @return class-string<IRequestPropertyMapper>
     * @throws ReflectionException
     */
    public function getMapperForProperty(ReflectionProperty $property): string;
}