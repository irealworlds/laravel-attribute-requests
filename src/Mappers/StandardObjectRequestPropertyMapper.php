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
     * @param ReflectionType|null $type
     *
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType|null $type): object
    {
        return (object) $input;
    }
}
