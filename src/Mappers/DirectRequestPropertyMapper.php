<?php

namespace Ireal\AttributeRequests\Mappers;

use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionNamedType;

/**
 * @implements IRequestPropertyMapper<mixed>
 */
readonly class DirectRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionNamedType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionNamedType $type): mixed
    {
        return $input;
    }
}