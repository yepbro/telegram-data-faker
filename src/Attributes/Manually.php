<?php

namespace YepBro\TelegramDataFaker\Attributes;

use Attribute;

/**
 * Свойство должно устанавливаться в ручную
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Manually
{
    //
}
