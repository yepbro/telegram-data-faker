<?php

namespace YepBro\TelegramDataFaker\ChatBoostSource;

use YepBro\TelegramDataFaker\Attributes\Field;
use YepBro\TelegramDataFaker\Attributes\Optional;
use YepBro\TelegramDataFaker\FakeUserGenerator;

/**
 * The boost was obtained by the creation of a Telegram Premium or a Telegram Star giveaway. This boosts
 * the chat 4 times for the duration of the corresponding Telegram Premium subscription for Telegram Premium
 * giveaways and prize_star_count / 500 times for one year for Telegram Star giveaways.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcegiveaway
 */
class FakeChatBoostSourceGiveawayGenerator extends FakeChatBoostSource
{
    // Source of the boost, always “giveaway”
    #[Field]
    protected SourceEnum $source = SourceEnum::GIVEAWAY;

    // Identifier of a message in the chat with the giveaway; the message could have been deleted already.
    // May be 0 if the message isn't sent yet.
    #[Field]
    protected int $giveawayMessageId;

    // Optional. User that won the prize in the giveaway if any; for Telegram Premium giveaways only
    #[Field]
    #[Optional]
    protected FakeUserGenerator $user;

    // Optional. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
    #[Field]
    #[Optional]
    protected int $prizeStarCount;

    // Optional. True, if the giveaway was completed, but there was no user to win the prize
    #[Field]
    #[Optional]
    protected true $isUnclaimed;
}
