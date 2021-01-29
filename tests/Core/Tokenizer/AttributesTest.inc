<?php

/* testAttribute */
#[Attribute]
class CustomAttribute {}

/* testAttributeWithParams */
#[Attribute(Attribute::TARGET_CLASS)]
class SecondCustomAttribute {}

/* testAttributeWithNamedParam */
#[Attribute(flags: Attribute::TARGET_ALL)]
class AttributeWithParams {
    public function __construct($foo, array $bar) {}
}

/* testAttributeOnFunction */
#[CustomAttribute]
function attribute_on_function_test() {}

/* testAttributeOnFunctionWithParams */
#[AttributeWithParams('foo', bar: ['bar' => 'foobar'])]
function attribute_with_params_on_function_test() {}


