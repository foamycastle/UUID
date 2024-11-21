<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Provider\CounterProvider;
use Foamycastle\UUID\Provider\ProvidesBinary;
use Foamycastle\UUID\Provider\TimeProvider\GregorianTime;

class UUID implements ProvidesBinary,\Stringable
{
    protected const string FORMAT='%1$s-%2$s-%3$s-%4$s-%5$s';
    protected function __construct(protected readonly array $fields)
    {
    }

    public function __toString(): string
    {
        return sprintf(
            self::FORMAT,
            ...$this->fields
        );
    }
    function getBinary(): string
    {
        return hex2bin(join('',$this->fields));
    }

    public static function Version1():self
    {
        $timeProvider=Provider::GregorianTime();
        $fields=[
            Field::FromProvider($timeProvider)
                ->length(8)
                ->bitMask(0xFFFFFFFF),
            Field::FromProvider($timeProvider)
                ->length(4)
                ->shiftRight(32)
                ->bitMask(0xFFFF),
            Field::FromProvider($timeProvider)
                ->length(4)
                ->shiftLeft(48)
                ->applyVersion(1),
            Field::FromProvider(Provider::Counter(0,0x3ff))
                ->length(4)
                ->applyVariant(),
            Field::FromProvider(Provider::SystemNode())
                ->length(12)
        ];
        return new self($fields);
    }

}