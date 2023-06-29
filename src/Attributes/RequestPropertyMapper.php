<?php

namespace Ireal\AttributeRequests\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class RequestPropertyMapper
{
    /**
     * RequestPropertyMapper constructor method.
     *
     * @param string $mapper
     */
    public function __construct(
        public string $mapper
    ) {
    }
}
