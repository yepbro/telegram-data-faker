<?php

namespace YepBro\TelegramDataFaker\Attributes\Generators;

interface IGenerator
{
    public function generate(): mixed;
}
