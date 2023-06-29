<?php

namespace Ireal\AttributeRequests\Mappers;

use Carbon\Carbon;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<Carbon>
 */
readonly class CarbonRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType $type): Carbon
    {
        return Carbon::parse($input);
    }
}