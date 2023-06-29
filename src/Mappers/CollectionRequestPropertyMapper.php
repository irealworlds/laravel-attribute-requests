<?php

namespace Ireal\AttributeRequests\Mappers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionNamedType;

/**
 * @implements IRequestPropertyMapper<Collection>
 */
readonly class CollectionRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionNamedType $type
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionNamedType $type): Collection
    {
        return new Collection($input);
    }
}