<?php

namespace YepBro\TelegramDataFaker;

use YepBro\TelegramDataFaker\Attributes\Field;

/**
 * This object represents an incoming update. At most one of the optional parameters can be present in any given update.
 *
 * @link https://core.telegram.org/bots/api#update
 */
class FakeUpdate extends AbstractGenerator
{
    // The update's unique identifier.
    #[Field]
    protected int $updateId;
}
