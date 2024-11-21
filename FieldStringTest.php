<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field\FieldInt;
use Foamycastle\UUID\Field\FieldIntApi;
use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\CounterProvider;
use Foamycastle\UUID\Provider\ProvidesInt;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\Provider\TimeProvider\GregorianTime;
use Foamycastle\UUID\FieldApi;
use Foamycastle\UUID\Field;
use PHPUnit\Framework\TestCase;
class FieldStringTest extends TestCase
{

    public function testFactory(){
        $timeField=Field::FromProvider(GregorianTime::class);
        $this->assertInstanceOf(Field::class,$timeField);
        $this->assertInstanceOf(FieldApi::class,$timeField);
        $this->assertInstanceOf(FieldInt::class,$timeField);
        $this->assertInstanceOf(FieldIntApi::class,$timeField);

        $counterField=Field::FromProvider(CounterProvider::class,0,10,1);
        $this->assertInstanceOf(Field::class,$counterField);
        $this->assertInstanceOf(FieldApi::class,$counterField);
        $this->assertInstanceOf(FieldInt::class,$counterField);
        $this->assertInstanceOf(FieldIntApi::class,$counterField);

        $randomField=Field::FromProvider(RandomWord::class,48);
        $this->assertInstanceOf(Field::class,$randomField);
        $this->assertInstanceOf(FieldApi::class,$randomField);
        $this->assertInstanceOf(FieldInt::class,$randomField);
        $this->assertInstanceOf(FieldIntApi::class,$randomField);

        $hashMD5Provider=Provider::HashMD5('0a0f182d-77a9-481d-a812-fa61fcbe3118','testing');
        $hashSHAProvider=Provider::HashSHA1('0a0f182d-77a9-481d-a812-fa61fcbe3118','testing');

        $hashField1=Field::FromProvider($hashMD5Provider)->length(8);
        $hashField2=Field::FromProvider($hashMD5Provider)->length(4)->startAt(8);
        $hashField3=Field::FromProvider($hashMD5Provider)->length(4)->startAt(12)->applyVersion(3);
        $hashField4=Field::FromProvider($hashMD5Provider)->length(4)->startAt(16)->applyVariant();
        $hashField5=Field::FromProvider($hashMD5Provider)->length(12)->startAt(20);

        $output=sprintf(
            '%s-%s-%s-%s-%s',
            $hashField1,
            $hashField2,
            $hashField3,
            $hashField4,
            $hashField5,
        );
        $this->expectsOutput();
        echo $output.PHP_EOL;

        $hashField1=Field::FromProvider($hashSHAProvider)->length(8);
        $hashField2=Field::FromProvider($hashSHAProvider)->length(4)->startAt(8);
        $hashField3=Field::FromProvider($hashSHAProvider)->length(4)->startAt(12)->applyVersion(5);
        $hashField4=Field::FromProvider($hashSHAProvider)->length(4)->startAt(16)->applyVariant();
        $hashField5=Field::FromProvider($hashSHAProvider)->length(12)->startAt(20);

        $output=sprintf(
            '%s-%s-%s-%s-%s',
            $hashField1,
            $hashField2,
            $hashField3,
            $hashField4,
            $hashField5,
        );
        $this->expectsOutput();
        echo $output.PHP_EOL;
    }
    public function test__toString()
    {

    }

    public function testGetValue()
    {

    }

    public function testStartAt()
    {

    }

    public function testApplyVersion()
    {

    }

    public function testApplyVariant()
    {

    }

}
