<?php

namespace Ireal\AttributeRequests\Services;

use ArrayAccess;
use BackedEnum;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Ireal\AttributeRequests\Contracts\ITypeAnalysisService;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use SplFileInfo;

class TypeAnalysisService implements ITypeAnalysisService
{
    /**
     * @inheritDoc
     */
    public function isBooleanType(ReflectionNamedType $type): bool
    {
        return in_array($type->getName(), [
            'bool',
            'boolean',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function isCarbonType(ReflectionNamedType $type): bool
    {
        return $type->getName() === Carbon::class;
    }

    /**
     * @inheritDoc
     */
    public function isCollectionType(ReflectionNamedType $type): bool
    {
        return in_array($type->getName(), [
            Collection::class,
            Enumerable::class,
            ArrayAccess::class,
        ]);
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function isDateType(ReflectionNamedType $type): bool
    {
        // Dates are not a built-in type
        if ($type->isBuiltin()) {
            return false;
        }

        // If this type is exactly the DateTimeInterface, then this is a date
        if ($type->getName() === DateTimeInterface::class) {
            return true;
        }

        // If the type implements the DateTimeInterface, then this is a date
        $class = new ReflectionClass($type->getName());

        return $class->implementsInterface(DateTimeInterface::class);
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function isBackedEnumType(ReflectionNamedType $type): bool
    {
        // Dates are not a built-in type
        if ($type->isBuiltin()) {
            return false;
        }

        // If the type implements the BackedEnum, then this is an enum
        $class = new ReflectionClass($type->getName());

        return $class->implementsInterface(BackedEnum::class);
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function isMappableObjectType(ReflectionNamedType $type): bool
    {
        if ($type->isBuiltin()) {
            return $type->getName() === 'object';
        } else {
            $class = new ReflectionClass($type->getName());

            // Cannot map to interfaces
            if ($class->isInterface()) {
                return false;
            }

            // Enums should not be treated as mappable objects
            if ($class->isEnum()) {
                return false;
            }

            // If this class' constructor has required parameters, it cannot be mapped
            if ($class->getConstructor()?->getNumberOfRequiredParameters()) {
                return false;
            }

            return true;
        }
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function isFileType(ReflectionNamedType $type): bool
    {
        // Dates are not a built-in type
        if ($type->isBuiltin()) {
            return false;
        }

        // If this type is exactly the SplFileInfo, then this is a file
        if ($type->getName() === SplFileInfo::class) {
            return true;
        }

        // If the type implements the SplFileInfo, then this is a file
        $class = new ReflectionClass($type->getName());

        return $class->isSubclassOf(SplFileInfo::class);
    }

    /**
     * @inheritDoc
     */
    public function isIterableType(ReflectionNamedType $type): bool
    {
        if ($type->isBuiltin()) {
            return in_array($type->getName(), [
                'array',
                'iterable',
            ]);
        } else {
            $class = new ReflectionClass($type->getName());

            return $class->isIterable();
        }
    }

    /**
     * @inheritDoc
     */
    public function isNumericType(ReflectionNamedType $type): bool
    {
        return in_array($type->getName(), [
            'int',
            'float',
            'real',
        ]);
    }
}
