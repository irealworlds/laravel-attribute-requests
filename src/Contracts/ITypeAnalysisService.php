<?php

namespace Ireal\AttributeRequests\Contracts;

use ReflectionException;
use ReflectionNamedType;

interface ITypeAnalysisService
{
    /**
     * Check if the given {@link $type} is a boolean type.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isBooleanType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is a Carbon type.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isCarbonType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is a Collection type.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isCollectionType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is a DateTime type.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isDateType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is a file type.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isFileType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is a backed enum type.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isBackedEnumType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is an object type to which properties can be mapped.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isMappableObjectType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is numeric.
     *
     * @param ReflectionNamedType $type
     * @return bool
     * @throws ReflectionException
     */
    public function isNumericType(ReflectionNamedType $type): bool;

    /**
     * Check if the given {@link $type} is numeric.
     *
     * @param ReflectionNamedType $type
     * @return bool
     */
    public function isIterableType(ReflectionNamedType $type): bool;
}