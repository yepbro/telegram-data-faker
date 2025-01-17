<?php

namespace YepBro\TelegramDataFaker;

use BackedEnum;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use YepBro\TelegramDataFaker\Attributes\Conditions\ICondition;
use YepBro\TelegramDataFaker\Attributes\Field;
use YepBro\TelegramDataFaker\Attributes\Generators\IGenerator;
use YepBro\TelegramDataFaker\Attributes\Manually;
use YepBro\TelegramDataFaker\Attributes\OnlyHasKey;
use YepBro\TelegramDataFaker\Attributes\Optional;

abstract class AbstractGenerator
{
    /**
     * JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
     */
    protected int $jsonFlags = 480;

    /**
     * @var ReflectionProperty[]
     */
    protected array $fields;

    protected array $customAttributes = [];

    protected array $keys = [];

    protected ModeEnum $mode = ModeEnum::RANDOM;

    /**
     * Шанс (в процентах) получить null для nullable-свойств
     */
    protected int $globalNullChance = 10;

    /**
     * Шанс (в процентах) получить true для boolean-свойств
     */
    protected int $globalTrueChance = 50;

    public function __construct(array $attributes = [])
    {
        $this->customAttributes = $attributes;
        $this->fields = $this->getFields();
    }

    public static function make(array $attributes = [], ModeEnum $mode = ModeEnum::RANDOM): static
    {
        return new static($attributes)
            ->setMode($mode);
    }

    public static function min(array $attributes = []): static
    {
        return static::make($attributes, ModeEnum::MIN);
    }

    public static function max(array $attributes = []): static
    {
        return static::make($attributes, ModeEnum::MAX);
    }

    public function setKeys(array $keys): static
    {
        $this->keys = $keys;

        return $this;
    }

    public function addKey(string $key): static
    {
        if (!in_array($key, $this->keys)) {
            $this->keys[] = $key;
        }

        return $this;
    }

    public function setMode(ModeEnum $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function setJsonFlags(int $flags): static
    {
        $this->jsonFlags = $flags;

        return $this;
    }

    public function setGlobalNullChance(int $chance): static
    {
        $this->globalNullChance = $chance < 0 ? 0 : min($chance, 100);

        return $this;
    }

    public function setGlobalTrueChance(int $chance): static
    {
        $this->globalTrueChance = $chance < 0 ? 0 : min($chance, 100);

        return $this;
    }

    protected function isChance(int $chance): bool
    {
        if ($chance === 0) {
            return false;
        }

        return mt_rand(1, 100) <= $chance;
    }

    protected function fill(array $attributes): void
    {
        foreach ($this->fields as $field) {
            $name = $field->getName();

            $attrKey = $this->toSnakeCase($name);
            $attrValue = $attributes[$attrKey] ?? new None;

            $hasOptionalAttribute = !empty($field->getAttributes(Optional::class));
            $hasCustomValue = !($attrValue instanceof None);
            $isMinMode = $this->mode === ModeEnum::MIN;

            // Если есть предустановленное значение для свойства, то всегда его устанавливаем
            if ($hasCustomValue) {
                $this->{$name} = $attrValue;
                continue;
            }

            // Если у свойства атрибут Skip, то пропускаем
            if ($this->hasManuallyAttribute($field)) {
                continue;
            }

            // Если у свойства атрибут OnlyHasKey и ключ не входит в список ключей, то пропускаем
            if ($onlyHasKeyAttribute = $this->getOnlyHasKeyAttribute($field)) {
                if (!in_array($onlyHasKeyAttribute->getArguments()[0], $this->keys)) {
                    continue;
                }
            }

            // Если у свойства атрибут Optional и генерация в минимальном режиме, то пропускаем
            if ($hasOptionalAttribute && $isMinMode) {
                continue;
            }

            // Если у свойства атрибут Optional и генерация в случайном режиме и выпал вариант пропустить, то пропускаем
            if ($hasOptionalAttribute && $this->mode === ModeEnum::RANDOM && mt_rand(0, 1) === 0) {
                continue;
            }

            // Если у свойства есть

            $type = $field->getType();

            // Если свойство nullable и выпал вариант установить null, то устанавливаем его
            if ($type->allowsNull() && $this->isChance($this->globalNullChance)) {
                $this->{$name} = null;
                continue;
            }

            if ($this->hasUnfulfilledOtherFieldCondition($field)) {
                continue;
            }

            // Если свойство имеет генератор, то берем значение из него
            if ($this->hasGenerator($field)) {
                $this->{$name} = $this->getGenerator($field)->generate();
                continue;
            }

            // Генерация по умолчанию на основе типов свойств
            $defaultValue = $field->getDefaultValue();
            if ($defaultValue === null) {
                $typeName = $type->getName();

                $this->{$name} = match (true) {
                    $typeName === 'int' => mt_rand(1000000, 9999999),
                    $typeName === 'bool' => $this->isChance($this->globalTrueChance),
                    $typeName === 'true' => true,
                    $typeName === 'false' => false,
                    $typeName === 'null' => null,
                    $typeName === 'string' => str_replace('==', '', base64_encode(mt_rand())),
                    class_exists($typeName) => forward_static_call([$typeName, 'make']),
                };
            }
        }
    }

    /**
     * Возвращает объект в виде массива для свойств, которые имеют атрибут Field
     *
     * - если свойство объекта имеет атрибут Optional, то оно добавляется в массив только если оно инициализировано
     */
    public function toArray(): array
    {
        $this->fill($this->customAttributes);

        $data = [];

        foreach ($this->fields as $field) {
            if (isset($this->{$field->getName()}) === false && $this->hasSkippedAttribute($field)) {
                continue;
            }

            $value = $this->{$field->getName()};

            $data[$this->toSnakeCase($field->getName())] = match (true) {
                $value instanceof BackedEnum => $value->value,
                $value instanceof AbstractGenerator => $value->toArray(),
                default => $value,
            };
        }

        return $data;

    }

    public function hasUnfulfilledOtherFieldCondition($field): bool
    {
        $pattern = '/Conditions\\\If[a-zA-Z]+$/';

        $hasCondition = array_any(
            $field->getAttributes(),
            fn(ReflectionAttribute $a) => preg_match($pattern, $a->getName()),
        );

        if ($hasCondition) {
            /** @var ReflectionAttribute $attribute */
            $attribute = array_find(
                $field->getAttributes(),
                fn(ReflectionAttribute $a) => preg_match($pattern, $a->getName()),
            );

            /** @var ICondition $checker */
            $checker = $attribute->newInstance();

            return !$checker->has($this->{$checker->field});
        }

        return false;
    }

    public function hasSkippedAttribute(ReflectionProperty $field): bool
    {
        return $this->hasOptionalAttribute($field) || $this->hasManuallyAttribute($field);
    }

    protected function hasGenerator(ReflectionProperty $field): bool
    {
        $pattern = '/Generators\\\Type[a-z]+$/i';

        return array_any($field->getAttributes(), fn(ReflectionAttribute $a) => preg_match($pattern, $a->getName()));
    }

    protected function getGenerator(ReflectionProperty $field): IGenerator
    {
        $pattern = '/Generators\\\Type[a-z]+$/i';

        /** @var ReflectionAttribute $attribute */
        $attribute = array_find(
            $field->getAttributes(),
            fn(ReflectionAttribute $a) => preg_match($pattern, $a->getName()),
        );

        return $attribute->newInstance();
    }

    protected function hasManuallyAttribute(ReflectionProperty $field): bool
    {
        return !empty($field->getAttributes(Manually::class));
    }

    protected function hasOptionalAttribute(ReflectionProperty $field): bool
    {
        return !empty($field->getAttributes(Optional::class));
    }

    protected function getOnlyHasKeyAttribute(ReflectionProperty $field): ?ReflectionAttribute
    {
        return array_find($field->getAttributes(), fn(ReflectionAttribute $a) => $a->getName() === OnlyHasKey::class);
    }

    protected function getFields(): array
    {
        $reflectionClass = new ReflectionClass($this);

        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);

        return array_filter(
            $properties,
            fn(ReflectionProperty $property) => $property->getAttributes(Field::class),
        );
    }

    protected function toCamelCase(string $name): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
    }

    protected function toSnakeCase(string $name): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
    }

    public function toJson(?int $flags = null): false|string
    {
        $flags ??= $this->jsonFlags;

        return json_encode($this->toArray(), $flags);
    }
}
