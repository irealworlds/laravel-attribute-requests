<?php

namespace Ireal\AttributeRequests\Mappers;

use Ireal\AttributeRequests\Contracts\IRequestPropertyMapper;
use ReflectionType;

/**
 * @implements IRequestPropertyMapper<bool>
 */
readonly class BooleanRequestPropertyMapper implements IRequestPropertyMapper
{
    /**
     * @inheritDoc
     */
    public function map(mixed $input, ReflectionType|null $type): bool
    {
        if (is_string($input)) {
            if ($input === '0') {
                return false;
            }
            if ($input === '1') {
                return true;
            }
        }

        return filter_var($input, FILTER_VALIDATE_BOOLEAN);
    }
}
