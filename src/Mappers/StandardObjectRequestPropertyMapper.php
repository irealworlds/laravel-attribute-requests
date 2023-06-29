<?php

namespace Ireal\AttributeRequests\Mappers;

use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionNamedType;

/**
 * @implements IRequestPropertyMapper<object>
 */
readonly class StandardObjectRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionNamedType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionNamedType $type): object
    {
        return (object) $input;
    }
}