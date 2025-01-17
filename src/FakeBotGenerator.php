<?php

namespace YepBro\TelegramDataFaker;

use YepBro\TelegramDataFaker\Attributes\Field;

class FakeBotGenerator extends FakeUserGenerator
{
    #[Field]
    protected bool $isBot = true;
}
