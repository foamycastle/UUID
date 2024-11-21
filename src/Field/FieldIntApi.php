<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\FieldApi;

/**
 * Public API for FieldInt objects
 * @author Aaron Sollman <unclepong@gmail.com>
 */
interface FieldIntApi
{
    /**
     * Shift the bits of a field value towards signifigance
     * @param int the number of bits to shift
     */
    function shiftLeft(int $bits):FieldApi&FieldIntApi;

    /**
     * Shift the bits of a field value away from signifigance
     * @param int the number of bits to shift
     */
    function shiftRight(int $bits):FieldApi&FieldIntApi;

    /**
     * Apply a bitmask to the value of a field
     * @param int $input the bitmask value
     */
    function bitMask(int $input):FieldApi&FieldIntApi;
}