<?php

namespace YepBro\TelegramDataFaker\Attributes\Conditions;

use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_PROPERTY)]
class IfSame implements ICondition
{
    public string $field {
        get => $this->field;
        set => $this->field = $value;
    }

    public function __construct(string $field, public int|string|bool|BackedEnum $value)
    {
        $this->field = $field;
    }

    public function has(int|string|bool|BackedEnum $actual): bool
    {
        $value = $this->value instanceof BackedEnum ? $this->value->value : $this->value;

        return $actual === $value;
    }
}
