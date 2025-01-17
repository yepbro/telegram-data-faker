<?php

namespace YepBro\TelegramDataFaker;

use YepBro\TelegramDataFaker\Attributes\Conditions\IfAny;
use YepBro\TelegramDataFaker\Attributes\Conditions\IfSame;
use YepBro\TelegramDataFaker\Attributes\Field;
use YepBro\TelegramDataFaker\Attributes\Generators\TypeEnum;
use YepBro\TelegramDataFaker\Attributes\Generators\TypeFirstName;
use YepBro\TelegramDataFaker\Attributes\Generators\TypeLastName;
use YepBro\TelegramDataFaker\Attributes\Generators\TypeUsername;
use YepBro\TelegramDataFaker\Attributes\Manually;
use YepBro\TelegramDataFaker\Attributes\Optional;
use YepBro\TelegramDataFaker\ChatBoostSource\ChatTypeEnum;

/**
 * This object represents a chat.
 *
 * @link https://core.telegram.org/bots/api#chat
 */
class FakeChatGenerator extends AbstractGenerator
{
    // Unique identifier for this chat.
    #[Field]
    protected int $id;

    // Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
    #[Field]
    #[TypeEnum(ChatTypeEnum::class)]
    protected string $type;

    // Optional. Title, for supergroups, channels and group chats
    #[Field]
    #[Optional]
    #[IfAny('type', [ChatTypeEnum::SUPERGROUP, ChatTypeEnum::GROUP, ChatTypeEnum::CHANNEL])]
    protected string $title;

    // Optional. Username, for private chats, supergroups and channels if available
    #[Field]
    #[Optional]
    #[IfAny('type', [ChatTypeEnum::SUPERGROUP, ChatTypeEnum::PRIVATE, ChatTypeEnum::CHANNEL])]
    #[TypeUsername]
    protected string $username;

    // Optional. First name of the other party in a private chat
    #[Field]
    #[Optional]
    #[IfSame('type', ChatTypeEnum::PRIVATE)]
    #[TypeFirstName]
    protected string $firstName;

    // Optional. Last name of the other party in a private chat
    #[Field]
    #[Optional]
    #[IfSame('type', ChatTypeEnum::PRIVATE)]
    #[TypeLastName]
    protected string $lastName;

    // Optional. True, if the supergroup chat is a forum (has topics enabled)
    // https://telegram.org/blog/topics-in-groups-collectible-usernames#topics-in-groups
    #[Field]
    #[Manually]
    protected true $isForum;

    public function private(): static
    {
        $this->customAttributes['type'] = ChatTypeEnum::PRIVATE->value;

        return $this;
    }

    public function channel(): static
    {
        $this->customAttributes['type'] = ChatTypeEnum::CHANNEL->value;

        return $this;
    }

    public function group(): static
    {
        $this->customAttributes['type'] = ChatTypeEnum::GROUP->value;

        return $this;
    }

    public function supergroup(): static
    {
        $this->customAttributes['type'] = ChatTypeEnum::SUPERGROUP->value;

        return $this;
    }

    public function forum(): static
    {
        $this->customAttributes['is_forum'] = true;
        $this->customAttributes['type'] = ChatTypeEnum::SUPERGROUP->value;

        return $this;
    }
}
