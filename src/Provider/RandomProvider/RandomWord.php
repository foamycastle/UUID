<?php

namespace Foamycastle\UUID\Provider\RandomProvider;

use Foamycastle\UUID\Provider\ProvidesBinary;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\Provider\ProvidesInt;
use Foamycastle\UUID\Provider\RandomProvider;
use Foamycastle\UUID\ProviderApi;

class RandomWord extends RandomInt
{
    protected function __construct(private readonly int $bitLength)
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