<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Provider\CounterProvider;
use Foamycastle\UUID\Provider\NodeProvider;
use PhpParser\NodeDumper;
use PHPUnit\Framework\TestCase;
use Foamycastle\UUID\Provider\GregorianTime;
use SebastianBergmann\LinesOfCode\Counter;

class FieldHexTest extends TestCase
{

    public function test__construct()
    {
        $hex = new FieldHex('ff00');
        $hexBad= new FieldHex('suck it');
        $this->assertFalse($hexBad->hasError());
    }

    public function testToInt()
    {
        $hexBig = new FieldHex('f0');
        $this->assertEquals(240,$hexBig->toInt());

        $hexBig = new FieldHex('f0e');
        $this->assertEquals(3854,$hexBig->toInt());

        $hexBig = new FieldHex('f0e5');
        $this->assertEquals(61669,$hexBig->toInt());

        $hexBig = new FieldHex('f0e5a');
        $this->assertEquals(986714,$hexBig->toInt());

        $hexBig = new FieldHex('1f0e5a');
        $this->assertEquals(2035290,$hexBig->toInt());

        $hexBig = new FieldHex('d1f0e5a');
        $this->assertEquals(220139098,$hexBig->toInt());

        $hexBig = new FieldHex('3d1f0e5a');
        $this->assertEquals(1025445466,$hexBig->toInt());

        $hexBig = new FieldHex('ffffffffffffffff');
        $this->assertEquals( PHP_INT_MAX|PHP_INT_MIN,$hexBig->toInt());

        $hexBig = new FieldHex('fed34fffffffff');
        $this->assertEquals( 71726984635351039,$hexBig->toInt());

        $hexOther= new FieldHex($hexBig);
        $this->assertEquals(71726984635351039,$hexOther->toInt());

    }

    public function testToHex()
    {
        $toHex= new FieldHex('ff00');
        $this->assertEquals('ff00',$toHex->toHex());
    }

    public function testOrValue()
    {
        $hex    =   new FieldHex('ff00');
        $orValue= 0x23;
        $this->assertEquals('ff23',$hex->orValue($orValue)->toHex());
    }

    public function testAndValue()
    {
        $hex    =   new FieldHex('ee33');
        $andValue=  0xcc23;
        $this->assertEquals('cc23',$hex->andValue($andValue)->toHex());
    }
    public function testFromProvider()
    {
        $timeProvider = new GregorianTime();
        $counterProvider = new CounterProvider(0,16383,1);
        $nodeProvider = NodeProvider::System();

        $timeData=FieldHex::FromProvider($timeProvider);
        $counterData=FieldHex::FromProvider($counterProvider);
        $nodeData=FieldHex::FromProvider($nodeProvider);

        $highFieldAndVersion=$timeData->shiftRight(48)->orValue(1<<12);
        $midField = $timeData->shiftRight(32)->andValue(0xFFFF);
        $lowField = $timeData->andValue(0xFFFFFFFF);

        $counterField = $counterData->orValue(2<<14);

        $nodeField= $nodeData;
        $this->expectsOutput();
        echo "$lowField-$midField-$highFieldAndVersion-$counterField-$nodeField";
    }

}
