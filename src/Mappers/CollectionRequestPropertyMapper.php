<?php

namespace Ireal\AttributeRequests\Mappers;

use Illuminate\Support\Collection;
use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<Collection>
 */
readonly class CollectionRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @param ReflectionType|null $type
     *
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType|null $type): Collection
    {
        return new Collection($input);
    }
}
