<?php

namespace YepBro\TelegramDataFaker\ChatBoostSource;

use YepBro\TelegramDataFaker\Attributes\Field;
use YepBro\TelegramDataFaker\FakeUserGenerator;

/**
 * The boost was obtained by subscribing to Telegram Premium or by gifting a Telegram Premium subscription
 * to another user.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcepremium
 */
class FakeChatBoostSourcePremiumGenerator extends FakeChatBoostSource
{
    // Source of the boost, always “premium”
    #[Field]
    protected SourceEnum $source = SourceEnum::PREMIUM;

    #[Field]
    protected FakeUserGenerator $user;
}
