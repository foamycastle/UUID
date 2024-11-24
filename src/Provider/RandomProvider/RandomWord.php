<?php

namespace Foamycastle\UUID\Provider\RandomProvider;



/**
 * Provides a random integer of a specified bit length to a field object
 * @author Aaron Sollman <unclepong@gmail.com>
 */
class RandomWord extends RandomInt
{
    public function __construct(private readonly int $bitLength)
    {
        if($this->bitLength==64){
            $max=(PHP_INT_MAX | PHP_INT_MIN);
            $min=PHP_INT_MAX;
        }else{
            $min=(2**($this->bitLength-1));
            $max=(2**$this->bitLength)-1;
        }
        parent::__construct($min,$max);
    }
}