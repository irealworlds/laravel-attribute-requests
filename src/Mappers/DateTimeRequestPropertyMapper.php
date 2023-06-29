<?php

namespace Ireal\AttributeRequests\Mappers;

use Carbon\Carbon;
use DateTimeInterface;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<DateTimeInterface>
 */
readonly class DateTimeRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionType|null $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType|null $type): DateTimeInterface
    {
        return Carbon::parse($input)->toDateTime();
    }
}