<?php

namespace YepBro\TelegramDataFaker\ChatBoostSource;

use YepBro\TelegramDataFaker\AbstractGenerator;

/**
 * This object describes the source of a chat boost. It can be one of
 * - ChatBoostSourcePremium
 * - ChatBoostSourceGiftCode
 * - ChatBoostSourceGiveaway
 *
 * @link https://core.telegram.org/bots/api#chatboostsource
 */
abstract class FakeChatBoostSource extends AbstractGenerator
{
    protected SourceEnum $source;
}
