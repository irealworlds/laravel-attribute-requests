<?php

namespace Ireal\AttributeRequests\Contracts;

use InvalidArgumentException;
use ReflectionType;

/**
 * The contract that has to be implemented for all request property mappers.
 *
 * @template TOutput The expected output type for this mapper.
 */
interface IRequestPropertyMapper
{
    /**
     * Map the received request input to the requested output type.
     *
     * @param mixed|null $input
     * @param ReflectionType|null $type
     * @return TOutput
     * @throws InvalidArgumentException
     */
    public function map(mixed $input, ReflectionType|null $type): mixed;
}