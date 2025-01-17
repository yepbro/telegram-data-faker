<?php

namespace YepBro\TelegramDataFaker\ChatBoostSource;

enum SourceEnum: string
{
    case PREMIUM = 'premium';
    case GIFT_CODE = 'gift_code';
    case GIVEAWAY = 'giveaway';
}
