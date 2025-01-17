<?php

namespace YepBro\TelegramDataFaker\Tests\Generators;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;
use YepBro\TelegramDataFaker\Attributes\Generators\TypeInteger;
use YepBro\TelegramDataFaker\Tests\PhpUnitBase;

#[Group('Generators')]
#[CoversClass(TypeInteger::class)]
class TypeIntegerTest extends PhpUnitBase
{
    #[TestDox('TypeInteger default test success')]
    public function test_success(): void
    {
        $this->assertIsInt(new TypeInteger()->generate());
    }

    #[TestWith([1, 5])]
    #[TestWith([2, 3])]
    #[TestWith([2, 2])]
    #[TestWith([-5, -2])]
    public function test_in_range(int $a, int $b): void
    {
        $actual = new TypeInteger($a, $b)->generate();

        $this->assertLessThanOrEqual($b, $actual);
        $this->assertGreaterThanOrEqual($a, $actual);
    }
}