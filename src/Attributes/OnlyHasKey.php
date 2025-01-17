<?php

namespace YepBro\TelegramDataFaker\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class OnlyHasKey
{
    public function __construct(public string $key)
    {
        //
    }
}
