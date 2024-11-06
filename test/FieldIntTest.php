<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Field\FieldString;
use PHPUnit\Framework\TestCase;
use Foamycastle\UUID\Field\FieldInt;

class FieldIntTest extends TestCase
{
    function test__construct()
    {
        //test creates a FieldInt Object
        $fieldInt=new FieldInt(16);
        $this->assertInstanceOf('Foamycastle\UUID\Field\FieldInt', $fieldInt);
        $this->assertInstanceOf('Foamycastle\UUID\FieldInt', $fieldInt);
        $this->assertInstanceOf('Foamycastle\UUID\FieldApi', $fieldInt);
        $this->assertInstanceOf('Foamycastle\UUID\Field\FieldIntApi', $fieldInt);

        //test creates a FieldInt Object with a value greater than the bit length parameter permits
        $fieldInt = new FieldInt(16,70000);
        $this->assertEquals(4464, $fieldInt->getValue());

        //test creates a FieldInt Object with a value greater than the bit length parameter permits but then
        //takes the bit offset value into account
        $fieldInt = new FieldInt(16,70000,0,1);
        $this->assertEquals(35000, $fieldInt->getValue());

        //test creates a FieldInt Object with a bit offset that is greater than the bit length
        //the two properties are then made equal and the output should be 1
        $fieldInt = new FieldInt(16,65535,0,17);
        $this->assertEquals(65535, $fieldInt->getValue());

        //test creates a FieldInt Object with a value greater than the bit length parameter permits but then
        //takes the bit offset value into account
        $fieldInt = new FieldInt(16,70000,0,1);
        $this->assertEquals(35000, $fieldInt->getValue());

    }
    function testGetValue(){
        //this test create a FieldInt object and assigns a value which is then retrieved
        $fieldInt=new FieldInt(16,4000);
        $this->assertEquals(4000, $fieldInt->getValue());
    }

    function testGetAdjustedValue(){
        //this test creates a FieldInt object, assigns it a value and a bit offset, and then retrieves the adjusted value
        $fieldInt=new FieldInt(16,4000,0,4);
        $this->assertEquals(64000, $fieldInt->getAdjustedFieldValue());
    }
    function testCombinedValue()
    {
        //this test creates two FieldInt objects and combines their value
        $fieldInt=new FieldInt(16,4000);
        $fieldIntToo=new FieldInt(16,8622);

        //$fieldInt will reference $fieldIntToo
        $fieldInt->setLink($fieldIntToo);

        $value=$fieldInt->getCombinedValue(FieldInt::COMBINE_OR);
        $this->assertEquals(12206, $value);
    }

    function testMutator()
    {
        //this test create 2 field of different types. one field then transforms and assumes the data of the other
        $fieldInt=new FieldInt(32,4000);
        $fieldString= new FieldString('77278adb',8);

        $fieldInt->setLink($fieldString);

        $newField=$fieldInt->mutateField();
        $this->assertEquals(1999080155, $newField->getValue());

    }

}
