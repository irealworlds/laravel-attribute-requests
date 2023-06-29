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
     * @param ReflectionType|null $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType|null $type): mixed
    {
        return $input;
    }
}