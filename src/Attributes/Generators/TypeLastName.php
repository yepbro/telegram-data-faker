<?php

namespace YepBro\TelegramDataFaker\Attributes\Generators;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class TypeLastName implements IGenerator
{
    public function generate(): string
    {
        $values = self::items();

        return $values[array_rand($values)];
    }

    public function items(): array
    {
        return [
            'Ivanov',
            'Petrov',
            'Sidor',
        ];
    }
}
