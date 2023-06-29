<?php

namespace Ireal\AttributeRequests\Mappers;

use BackedEnum;
use InvalidArgumentException;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionNamedType;
use ReflectionType;

/**
 * @template TEnum of BackedEnum
 * @implements IRequestPropertyMapper<TEnum>
 */
readonly class BackedEnumRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType|null $type): BackedEnum
    {
        if ($type instanceof ReflectionNamedType) {
            /** @var BackedEnum $class */
            $class = $type->getName();
            return ($class)::from($input);
        } else {
            throw new InvalidArgumentException("The provided type is not a valid BackedEnum type.");
        }
    }
}