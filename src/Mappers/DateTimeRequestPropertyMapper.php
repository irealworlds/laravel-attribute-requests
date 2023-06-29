<?php

namespace Ireal\AttributeRequests\Mappers;

use Carbon\Carbon;
use DateTimeInterface;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionNamedType;

/**
 * @implements IRequestPropertyMapper<DateTimeInterface>
 */
readonly class DateTimeRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionNamedType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionNamedType $type): DateTimeInterface
    {
        return Carbon::parse($input)->toDateTime();
    }
}