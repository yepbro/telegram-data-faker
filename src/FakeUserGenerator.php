<?php

namespace YepBro\TelegramDataFaker;

use YepBro\TelegramDataFaker\Attributes\Field;
use YepBro\TelegramDataFaker\Attributes\Generators\TypeLanguageCode;
use YepBro\TelegramDataFaker\Attributes\OnlyHasKey;
use YepBro\TelegramDataFaker\Attributes\Optional;

/**
 * This object represents a Telegram user or bot.
 *
 * @link https://core.telegram.org/bots/api#user
 */
class FakeUserGenerator extends AbstractGenerator
{
    // Unique identifier for this user or bot.
    #[Field]
    protected int $id;

    // True, if this user is a bot
    #[Field]
    protected bool $isBot = false;

    // User's or bot's first name
    #[Field]
    protected string $firstName;

    // Optional. User's or bot's last name
    #[Field]
    #[Optional]
    protected string $lastName;

    // Optional. User's or bot's username
    #[Field]
    #[Optional]
    protected string $username;

    // Optional. IETF language tag of the user's language
    // https://en.wikipedia.org/wiki/IETF_language_tag
    #[Field]
    #[Optional]
    #[TypeLanguageCode]
    protected string $languageCode;

    // Optional. True, if this user is a Telegram Premium user
    #[Field]
    #[Optional]
    protected true $isPremium;

    // Optional. True, if this user added the bot to the attachment menu
    #[Field]
    #[Optional]
    protected true $addedToAttachmentMenu;

    // Optional. True, if the bot can be invited to groups.
    // Returned only in getMe.
    #[Field]
    #[Optional]
    #[OnlyHasKey('getMe')]
    protected bool $canJoinGroups;

    // Optional. True, if privacy mode is disabled for the bot.
    // https://core.telegram.org/bots/features#privacy-mode
    // Returned only in getMe.
    #[Field]
    #[Optional]
    #[OnlyHasKey('getMe')]
    protected bool $canReadAllGroupMessages;

    // Optional. True, if the bot supports inline queries.
    // Returned only in getMe.
    #[Field]
    #[Optional]
    #[OnlyHasKey('getMe')]
    protected bool $supportsInlineQueries;

    // Optional. True, if the bot can be connected to a Telegram Business account to receive its messages.
    // Returned only in getMe.
    #[Field]
    #[Optional]
    #[OnlyHasKey('getMe')]
    protected bool $canConnectToBusiness;

    // Optional. True, if the bot has a main Web App.
    // Returned only in getMe.
    #[Field]
    #[Optional]
    #[OnlyHasKey('getMe')]
    protected bool $hasMainWebApp;

    public function getMe(): static
    {
        $this->addKey('getMe');

        return $this;
    }
}
