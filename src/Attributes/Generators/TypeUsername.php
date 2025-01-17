<?php

namespace YepBro\TelegramDataFaker\Attributes\Generators;

use Attribute;
use Illuminate\Support\Str;

#[Attribute(Attribute::TARGET_PROPERTY)]
class TypeUsername implements IGenerator
{
    public function generate(): string
    {
        return ltrim('dssDFGcvxdgHcfFGCB', '1234567890');
    }
}
