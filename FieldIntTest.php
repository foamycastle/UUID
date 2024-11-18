<?php

namespace Foamycastle\UUID\Field;

use PHPUnit\Framework\TestCase;

class FieldIntTest extends TestCase
{

    public function test__construct()
    {
        $fieldInt=new FieldInt(4);
        $this->assertEquals(4,$fieldInt->toInt());
        $this->assertEquals('04',$fieldInt->toHex());
        $fieldString=new FieldHex($fieldInt);
        $this->assertEquals(4,$fieldString->toInt());
        $this->assertEquals('04',$fieldString->toHex());
    }

}
