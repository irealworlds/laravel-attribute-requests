<?php

namespace Ireal\AttributeRequests\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class RequestPropertyName
{
    /**
     * RequestPropertyName constructor method.
     *
     * @param string $name
     */
    public function __construct(
        public string $name
    ) {
    }
}
