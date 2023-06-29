<?php

namespace Ireal\AttributeRequests\Attributes;

use Attribute;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use Stringable;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
readonly class ValidateRule
{
    public ValidationRule|string $rule;

    /**
     * @param ValidationRule|Stringable|string|mixed $rule
     */
    public function __construct( mixed $rule) {

        if ($rule instanceof Stringable) {
            $this->rule = (string) $rule;
        } else if (is_string($rule)) {
            $this->rule = $rule;
        } else if ($rule instanceof ValidationRule) {
            $this->rule = $rule;
        } else {
            throw new InvalidArgumentException("The provided rule is not an instance of ValidationRule or a string.");
        }
    }

}