<?php

namespace Foamycastle\UUID\Field;

use PHPUnit\Framework\TestCase;

class FieldStringTest extends TestCase
{

    public function test__construct()
    {
        //this tests the constructor of field string, and then tests the __toString() method
        $fieldString = new FieldString('you fucked my sister');
        $this->assertEquals('you fucked my sister',(string)$fieldString);
    }

    public function testPad()
    {
        //this function tests the string padding flag
        $fieldString=new FieldString('0ad0f',8,true);
        $this->assertEquals('0000ad0f',(string)$fieldString);
    }

    public function testLinkingAndOutput()
    {
        //this test constructs 2 fields of different types, int and string.
        //the string field will link with and mutate the value of the int field
        //and output the value as a padded string
        $fieldString=new FieldString('0ad0f',8,true);
        $fieldInt=new FieldInt(32,288832);

        $fieldString->setLink($fieldInt);
        $this->assertEquals('0000ad0f',(string)$fieldString);
    }

}
