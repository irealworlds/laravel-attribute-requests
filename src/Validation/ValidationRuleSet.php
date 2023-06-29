<?php

namespace Ireal\AttributeRequests\Validation;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Validation\{Rule, ValidationRule};
use Illuminate\Support\Collection;

readonly class ValidationRuleSet implements Arrayable
{
    /**
     * The rules currently in the set.
     *
     * @var Collection<string, Collection<ValidationRule|string>>
     */
    private Collection $rules;

    public function __construct()
    {
        $this->rules = new Collection();
    }

    /**
     * Add a new rule to this rule set.
     *
     * @param string $field
     * @param ValidationRule|Rule|string $rule
     * @return void
     */
    public function addRule(string $field, ValidationRule|Rule|string $rule): void
    {
        // Make sure there is a collection at field
        if (!$this->rules->has($field)) {
            $this->rules->put($field, new Collection());
        }

        // Add the rule to the field's set.
        $rules = $this->rules->get($field);
        $rules->push($rule);
        $this->rules->put($field, $rules);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->rules->toArray();
    }
}