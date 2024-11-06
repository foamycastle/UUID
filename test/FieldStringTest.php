<?php

namespace Foamycastle\UUID\Field;

use PHPUnit\Framework\TestCase;

class FieldStringTest extends TestCase
{
    public function test__construct()
    {
        /*
         * this tests the constructor of field string. the result should be blank
         * because the input is not a valid hex string
         */
        $fieldString = new FieldHex('you fucked my sister');
        $this->assertEquals('',(string)$fieldString);

        /**
         * This test constructs an object, specifies a max length
         */
        $fieldString = new FieldHex('ae01d6ef',4);
        $this->assertEquals('d6ef',(string)$fieldString);
    }

    public function testPad()
    {
        //this function tests the string padding flag
        $fieldString=new FieldHex('0ad0f',8,true);
        $this->assertEquals('0000ad0f',(string)$fieldString);
    }

    public function testLinkingAndOutput()
    {
        //this test constructs 2 fields of different types, int and string.
        //the string field will link with and mutate the value of the int field
        //and output the value as a padded string
        $fieldString=new FieldHex('0ad0f',8,true);
        $fieldInt=new FieldInt(32,288832);

        $fieldString->setLink($fieldInt);
        $fieldString = $fieldString->mutateField($fieldInt)->pad(true)->setCharLength(8);
        $this->assertEquals('00046840',(string)$fieldString);
    }

}
