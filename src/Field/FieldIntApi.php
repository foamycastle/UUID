<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\FieldApi;

interface FieldIntApi
{
    function shiftLeft(int $bits):FieldApi&FieldIntApi;
    function shiftRight(int $bits):FieldApi&FieldIntApi;
    function bitMask(int $input):FieldApi&FieldIntApi;
}