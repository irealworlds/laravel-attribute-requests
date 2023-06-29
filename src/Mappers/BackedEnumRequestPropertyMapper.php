<?php

namespace Ireal\AttributeRequests\Mappers;

use BackedEnum;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionNamedType;

/**
 * @template TEnum of BackedEnum
 * @implements IRequestPropertyMapper<TEnum>
 */
readonly class BackedEnumRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionNamedType $type): BackedEnum
    {
        /** @var BackedEnum $class */
        $class = $type->getName();
        return ($class)::from($input);
    }
}