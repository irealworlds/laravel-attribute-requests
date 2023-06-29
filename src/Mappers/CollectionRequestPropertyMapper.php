<?php

namespace Ireal\AttributeRequests\Mappers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<Collection>
 */
readonly class CollectionRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType $type): Collection
    {
        return new Collection($input);
    }
}