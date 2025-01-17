<?php

namespace YepBro\TelegramDataFaker\Attributes\Conditions;

use BackedEnum;

interface ICondition
{
    public string $field {
        get;
        set;
    }

    public function has(int|string|bool|BackedEnum $actual): bool;
}
