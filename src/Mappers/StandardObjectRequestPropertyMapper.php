<?php

namespace Ireal\AttributeRequests\Mappers;

use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<object>
 */
readonly class StandardObjectRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType $type): object
    {
        return (object) $input;
    }
}