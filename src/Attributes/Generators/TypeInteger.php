<?php

namespace YepBro\TelegramDataFaker\Attributes\Generators;

use Attribute;
use YepBro\TelegramDataFaker\Tests\Generators\TypeIntegerTest;

/**
 * Integer with min amd max options
 *
 * @see TypeIntegerTest
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class TypeInteger implements IGenerator
{
    public function __construct(
        protected int $min = 0,
        protected int $max = PHP_INT_MAX,
    )
    {
        //
    }

    public function generate(): int
    {
        return mt_rand($this->min, $this->max);
    }
}
