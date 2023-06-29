<?php

namespace Ireal\AttributeRequests\Mappers;

use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<mixed>
 */
readonly class DirectRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType $type): mixed
    {
        return $input;
    }
}