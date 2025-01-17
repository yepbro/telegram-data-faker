<?php

namespace YepBro\TelegramDataFaker\Attributes\Generators;

use Attribute;

/**
 * Integer with min amd max options
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class TypeEnum implements IGenerator
{
    public function __construct(
        protected string $enumClass,
    )
    {
        //
    }

    public function generate(): string
    {
        $values = call_user_func([$this->enumClass, 'cases']);

        return $values[array_rand($values)]->value;
    }
}
