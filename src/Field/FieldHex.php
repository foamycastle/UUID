<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\FieldApi;
use Foamycastle\UUID\Provider;

class FieldHex extends Field
{
    private const NOT_HEX_CHAR='/[^a-f0-9]+/i';

    public function __construct(
        string|int|Field $value
    )
    {
        if(is_string($value)) {
            //string is primitive, filter invalid characters
            $value=preg_replace(self::NOT_HEX_CHAR, '', $value);
            //if the input primitive is longer than 16 characters, take the final 16 characters
            $value=strlen($value)>16
                ? substr($value, -16)
                : $value;
        }
        if(is_int($value)){
            $value=dechex($value);
        }
        if($value instanceof FieldApi) {
            //string is valid because it comes from the pack() function inside FieldInt constructor
            $this->hexStringLength=$value->hexStringLength;
            $this->bitLength=$value->bitLength;
            $this->value=$value->value;
            return;
        }


        //hex string lengths and associated bit lengths
        $this->hexStringLength = strlen($value);
        $this->bitLength = $this->hexStringLength*4;

        //if the string is empty, fail
        if($this->hexStringLength==0){
            return;
        }
        $value = str_pad($value,16 , '0', STR_PAD_LEFT);
        $value= pack('H*', $value);

        $this->value=$value;
    }

    function __invoke(int|string|FieldApi $value): FieldApi
    {
        if(is_string($value)||($value instanceof FieldApi)) {
            return new self($value);
        }
        return new FieldInt($value);
    }



}