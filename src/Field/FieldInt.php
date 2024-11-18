<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\FieldApi;
use Foamycastle\UUID\Provider;

class FieldInt extends Field
{
    public function __construct(int|string|FieldApi $value)
    {
        if(is_string($value)) {
            if(!is_numeric($value)) {
                $value=match (true){
                    str_starts_with($value, '0x')=>pack("H*", substr($value, 2)),
                    default=>pack("J",intval(substr($value, 2)))
                };
            }else{
                $value=match (true) {
                    str_starts_with($value, '0')=>pack("J",(int)octdec(substr($value,1))),
                    default=>pack("J",intval($value)),
                };
            }
            $this->hexStringLength = strlen($value)*2;
            return;
        }
        if(is_int($value)) {
            $this->value = pack('J', $value);
            $this->hexStringLength = strlen($value)*2;
        }
        if($value instanceof FieldApi) {
            $this->value = (string)$value;
            $this->hexStringLength = $value->hexStringLength;
        }
    }

    function __invoke(int|string|FieldApi $value): FieldApi
    {
        return new self($value);
    }


}