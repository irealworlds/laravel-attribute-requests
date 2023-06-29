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
     *
     * @param mixed                                           $input
     * @param class-string<TMapper<TOutput>>|TMapper<TOutput> $mapper
     * @param ReflectionNamedType                             $type
     *
     * @throws BindingResolutionException
     *
     * @return TOutput
     */
    public function mapRequestValue(mixed $input, mixed $mapper, ReflectionNamedType $type): mixed;

    /**
     * Map the given {@link $input} using a mapper extracted from the given {@link $property}.
     *
     * @template TOutput
     *
     * @param mixed              $input
     * @param ReflectionProperty $property
     *
     * @throws BindingResolutionException
     *
     * @return TOutput
     */
    public function mapRequestValueForProperty(mixed $input, ReflectionProperty $property);

    /**
     * Get the mapper that should be applied for a property of the given {@link $type}.
     *
     * @param ReflectionNamedType $type
     *
     * @throws ReflectionException
     *
     * @return class-string<IRequestPropertyMapper>
     */
    public function getMapperForType(ReflectionNamedType $type): string;

    /**
     * Get the mapper that should be applied for the given {@link $property}.
     *
     * @param ReflectionProperty $property
     *
     * @throws ReflectionException
     *
     * @return class-string<IRequestPropertyMapper>
     */
    public function getMapperForProperty(ReflectionProperty $property): string;

    /**
     * Get the name of the incoming request property that should be mapped to the object {@link $property}.
     *
     * @param ReflectionProperty $property
     *
     * @throws ReflectionException
     *
     * @return class-string<IRequestPropertyMapper>
     */
    public function getRequestNameForProperty(ReflectionProperty $property): string;
}
