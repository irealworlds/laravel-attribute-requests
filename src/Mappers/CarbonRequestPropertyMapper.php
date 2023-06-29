<?php

namespace Ireal\AttributeRequests\Mappers;

use Carbon\Carbon;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionNamedType;

/**
 * @implements IRequestPropertyMapper<Carbon>
 */
readonly class CarbonRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionNamedType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionNamedType $type): Carbon
    {
        return Carbon::parse($input);
    }
}