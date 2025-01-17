<?php

namespace YepBro\TelegramDataFaker\ChatBoostSource;

enum ChatTypeEnum: string
{
    case PRIVATE = 'private';
    case GROUP = 'group';
    case SUPERGROUP = 'supergroup';
    case CHANNEL = 'channel';
}
