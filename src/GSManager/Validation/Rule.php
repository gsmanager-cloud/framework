<?php

namespace GSManager\Validation;

use GSManager\Contracts\Support\Arrayable;
use GSManager\Support\Arr;
use GSManager\Support\Traits\Macroable;
use GSManager\Validation\Rules\AnyOf;
use GSManager\Validation\Rules\ArrayRule;
use GSManager\Validation\Rules\Can;
use GSManager\Validation\Rules\Date;
use GSManager\Validation\Rules\Dimensions;
use GSManager\Validation\Rules\Email;
use GSManager\Validation\Rules\Enum;
use GSManager\Validation\Rules\ExcludeIf;
use GSManager\Validation\Rules\Exists;
use GSManager\Validation\Rules\File;
use GSManager\Validation\Rules\ImageFile;
use GSManager\Validation\Rules\In;
use GSManager\Validation\Rules\NotIn;
use GSManager\Validation\Rules\Numeric;
use GSManager\Validation\Rules\ProhibitedIf;
use GSManager\Validation\Rules\RequiredIf;
use GSManager\Validation\Rules\Unique;

class Rule
{
    use Macroable;

    /**
     * Get a can constraint builder instance.
     *
     * @param  string  $ability
     * @param  mixed  ...$arguments
     * @return \GSManager\Validation\Rules\Can
     */
    public static function can($ability, ...$arguments)
    {
        return new Can($ability, $arguments);
    }

    /**
     * Apply the given rules if the given condition is truthy.
     *
     * @param  callable|bool  $condition
     * @param  \GSManager\Contracts\Validation\ValidationRule|\GSManager\Contracts\Validation\InvokableRule|\GSManager\Contracts\Validation\Rule|\Closure|array|string  $rules
     * @param  \GSManager\Contracts\Validation\ValidationRule|\GSManager\Contracts\Validation\InvokableRule|\GSManager\Contracts\Validation\Rule|\Closure|array|string  $defaultRules
     * @return \GSManager\Validation\ConditionalRules
     */
    public static function when($condition, $rules, $defaultRules = [])
    {
        return new ConditionalRules($condition, $rules, $defaultRules);
    }

    /**
     * Apply the given rules if the given condition is falsy.
     *
     * @param  callable|bool  $condition
     * @param  \GSManager\Contracts\Validation\ValidationRule|\GSManager\Contracts\Validation\InvokableRule|\GSManager\Contracts\Validation\Rule|\Closure|array|string  $rules
     * @param  \GSManager\Contracts\Validation\ValidationRule|\GSManager\Contracts\Validation\InvokableRule|\GSManager\Contracts\Validation\Rule|\Closure|array|string  $defaultRules
     * @return \GSManager\Validation\ConditionalRules
     */
    public static function unless($condition, $rules, $defaultRules = [])
    {
        return new ConditionalRules($condition, $defaultRules, $rules);
    }

    /**
     * Get an array rule builder instance.
     *
     * @param  array|null  $keys
     * @return \GSManager\Validation\Rules\ArrayRule
     */
    public static function array($keys = null)
    {
        return new ArrayRule(...func_get_args());
    }

    /**
     * Create a new nested rule set.
     *
     * @param  callable  $callback
     * @return \GSManager\Validation\NestedRules
     */
    public static function forEach($callback)
    {
        return new NestedRules($callback);
    }

    /**
     * Get a unique constraint builder instance.
     *
     * @param  string  $table
     * @param  string  $column
     * @return \GSManager\Validation\Rules\Unique
     */
    public static function unique($table, $column = 'NULL')
    {
        return new Unique($table, $column);
    }

    /**
     * Get an exists constraint builder instance.
     *
     * @param  string  $table
     * @param  string  $column
     * @return \GSManager\Validation\Rules\Exists
     */
    public static function exists($table, $column = 'NULL')
    {
        return new Exists($table, $column);
    }

    /**
     * Get an in rule builder instance.
     *
     * @param  \GSManager\Contracts\Support\Arrayable|\BackedEnum|\UnitEnum|array|string  $values
     * @return \GSManager\Validation\Rules\In
     */
    public static function in($values)
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        return new In(is_array($values) ? $values : func_get_args());
    }

    /**
     * Get a not_in rule builder instance.
     *
     * @param  \GSManager\Contracts\Support\Arrayable|\BackedEnum|\UnitEnum|array|string  $values
     * @return \GSManager\Validation\Rules\NotIn
     */
    public static function notIn($values)
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        return new NotIn(is_array($values) ? $values : func_get_args());
    }

    /**
     * Get a required_if rule builder instance.
     *
     * @param  callable|bool  $callback
     * @return \GSManager\Validation\Rules\RequiredIf
     */
    public static function requiredIf($callback)
    {
        return new RequiredIf($callback);
    }

    /**
     * Get a exclude_if rule builder instance.
     *
     * @param  callable|bool  $callback
     * @return \GSManager\Validation\Rules\ExcludeIf
     */
    public static function excludeIf($callback)
    {
        return new ExcludeIf($callback);
    }

    /**
     * Get a prohibited_if rule builder instance.
     *
     * @param  callable|bool  $callback
     * @return \GSManager\Validation\Rules\ProhibitedIf
     */
    public static function prohibitedIf($callback)
    {
        return new ProhibitedIf($callback);
    }

    /**
     * Get a date rule builder instance.
     *
     * @return \GSManager\Validation\Rules\Date
     */
    public static function date()
    {
        return new Date;
    }

    /**
     * Get an email rule builder instance.
     *
     * @return \GSManager\Validation\Rules\Email
     */
    public static function email()
    {
        return new Email;
    }

    /**
     * Get an enum rule builder instance.
     *
     * @param  class-string  $type
     * @return \GSManager\Validation\Rules\Enum
     */
    public static function enum($type)
    {
        return new Enum($type);
    }

    /**
     * Get a file rule builder instance.
     *
     * @return \GSManager\Validation\Rules\File
     */
    public static function file()
    {
        return new File;
    }

    /**
     * Get an image file rule builder instance.
     *
     * @param  bool  $allowSvg
     * @return \GSManager\Validation\Rules\ImageFile
     */
    public static function imageFile($allowSvg = false)
    {
        return new ImageFile($allowSvg);
    }

    /**
     * Get a dimensions rule builder instance.
     *
     * @param  array  $constraints
     * @return \GSManager\Validation\Rules\Dimensions
     */
    public static function dimensions(array $constraints = [])
    {
        return new Dimensions($constraints);
    }

    /**
     * Get a numeric rule builder instance.
     *
     * @return \GSManager\Validation\Rules\Numeric
     */
    public static function numeric()
    {
        return new Numeric;
    }

    /**
     * Get an "any of" rule builder instance.
     *
     * @param  array
     * @return \GSManager\Validation\Rules\AnyOf
     *
     * @throws \InvalidArgumentException
     */
    public static function anyOf($rules)
    {
        return new AnyOf($rules);
    }

    /**
     * Get a contains rule builder instance.
     *
     * @param  \GSManager\Contracts\Support\Arrayable|\BackedEnum|\UnitEnum|array|string  $values
     * @return \GSManager\Validation\Rules\Contains
     */
    public static function contains($values)
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        return new Rules\Contains(is_array($values) ? $values : func_get_args());
    }

    /**
     * Compile a set of rules for an attribute.
     *
     * @param  string  $attribute
     * @param  array  $rules
     * @param  array|null  $data
     * @return object|\stdClass
     */
    public static function compile($attribute, $rules, $data = null)
    {
        $parser = new ValidationRuleParser(
            Arr::undot(Arr::wrap($data))
        );

        if (is_array($rules) && ! array_is_list($rules)) {
            $nested = [];

            foreach ($rules as $key => $rule) {
                $nested[$attribute.'.'.$key] = $rule;
            }

            $rules = $nested;
        } else {
            $rules = [$attribute => $rules];
        }

        return $parser->explode(ValidationRuleParser::filterConditionalRules($rules, $data));
    }
}
