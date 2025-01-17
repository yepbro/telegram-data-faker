<?php

namespace YepBro\TelegramDataFaker\Attributes\Conditions;

use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_PROPERTY)]
class IfAny implements ICondition
{
    public string $field {
        get => $this->field;
        set => $this->field = $value;
    }

    public function __construct(string $field, public array $values)
    {
        $this->field = $field;
    }

    public function has(int|string|bool|BackedEnum $actual): bool
    {
        foreach ($this->values as $value) {
            $value = $value instanceof BackedEnum ? $value->value : $value;

            if ($actual === $value) {
                return true;
            }
        }

        return false;
    }
}
