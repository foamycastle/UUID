<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Field\FieldHex;
use Foamycastle\UUID\Field\FieldInt;
use Foamycastle\UUID\Provider;
use PHPUnit\Framework\TestCase;

class GregorianTimeTest extends TestCase
{

    public function testGetData()
    {
        //tests the ability to extract an integer from the class
        $gregorianTime = Provider::GregorianTime(1);
        $counter = Provider::CounterProvider(0,300,1);
        $nodes = Provider::{'NodeProvider\\LinuxNodeProvider'}();

        //test a FieldInt with time output
        $fieldTimeLow=new FieldInt(32,$gregorianTime,8,0);
        $fieldTimeMid=new FieldInt(16,$gregorianTime,4,32);
        $fieldTimeHigh=new FieldInt(16,$gregorianTime,3,48);
        $fieldVersion=new FieldInt(16,1,1,12, FieldInt::OFFSET_LEFT);
        $fieldVariant=new FieldInt(16,2,1,14, FieldInt::OFFSET_LEFT);
        $fieldCounter=new FieldInt(16,$counter,4,0);
        $fieldTimeHighAndVersion=$fieldTimeHigh->combineWith($fieldVersion, FieldInt::COMBINE_OR);
        $fieldCounterAndVariant = $fieldVariant->combineWith($fieldCounter, FieldInt::COMBINE_OR);
        $fieldNode = new FieldHex($nodes->getFirstNode(),12,true);


        $this->expectsOutput();
        echo "$fieldTimeLow-$fieldTimeMid-$fieldTimeHighAndVersion\n";
    }



}
