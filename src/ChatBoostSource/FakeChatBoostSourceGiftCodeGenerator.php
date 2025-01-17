<?php

namespace YepBro\TelegramDataFaker\ChatBoostSource;

use YepBro\TelegramDataFaker\Attributes\Field;
use YepBro\TelegramDataFaker\FakeUserGenerator;

/**
 * The boost was obtained by the creation of Telegram Premium gift codes to boost a chat. Each such code boosts the
 * chat 4 times for the duration of the corresponding Telegram Premium subscription.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcegiftcode
 */
class FakeChatBoostSourceGiftCodeGenerator extends FakeChatBoostSource
{
    // Source of the boost, always “gift_code”
    #[Field]
    protected SourceEnum $source = SourceEnum::GIFT_CODE;

    // User for which the gift code was created
    #[Field]
    protected FakeUserGenerator $user;
}
