<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\Provider\CounterProvider;
use Foamycastle\UUID\Provider\TimeProvider\GregorianTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class FieldIntTest extends TestCase
{

    public function testGetValue()
    {
        $field=Field::FromProvider(GregorianTime::class)->length(3);
        Field::RefreshProviders($field);
        $value=$field->getValue();

        $this->assertIsInt($value);
        $this->expectsOutput();
        echo $value.PHP_EOL;
    }

    public function test__toString()
    {
        $field=Field::FromProvider(GregorianTime::class)->length(3);
        Field::RefreshProviders($field);
        $value=(string)$field;

        $this->assertIsString($value);
        $this->expectsOutput();
        echo $value.PHP_EOL;
    }

    public function testShiftRight()
    {
        $field=Field::FromProvider(GregorianTime::class)->length(4)->shiftRight(48)->applyVersion(1);
        Field::RefreshProviders($field);
        $value=(string)$field;
        $this->assertIsString($value);
        $this->expectsOutput();
        echo $value.PHP_EOL;
    }

    public function testBitMask()
    {
        $field=Field::FromProvider(GregorianTime::class)
                    ->length(4)
                    ->shiftRight(32)
                    ->bitMask(0xffff);
        Field::RefreshProviders($field);
        $value=(string)$field;
        $this->assertIsString($value);
        $this->expectsOutput();
        echo $value.PHP_EOL;
    }

    public function testApplyVariant()
    {
        $field=Field::FromProvider(CounterProvider::class, 0,100,1)
                    ->length(4)
                    ->applyVariant();
        Field::RefreshProviders($field);
        $value=(string)$field;
        $this->assertIsString($value);
        $this->expectsOutput();
        echo $value.PHP_EOL;
    }

    public function testApplyVersion()
    {
        $field=Field::FromProvider(GregorianTime::class)
                    ->length(4)
                    ->shiftRight(48)
                    ->applyVersion(1);
        Field::RefreshProviders($field);
        $value=(string)$field;
        $this->assertIsString($value);
        $this->expectsOutput();
        echo $value.PHP_EOL;
    }


}
