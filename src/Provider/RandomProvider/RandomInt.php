<?php

namespace Foamycastle\UUID\Provider\RandomProvider;

use Foamycastle\UUID\Provider\ProvidesBinary;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\Provider\ProvidesInt;
use Foamycastle\UUID\Provider\RandomProvider;
use Foamycastle\UUID\ProviderApi;

/**
 * Provide a random integer value to a field object. 
 * @author Aaron Sollman
 */
class RandomInt extends RandomProvider implements ProvidesInt, ProvidesHex, ProvidesBinary
{
    public function __construct(
        private readonly int $min,
        private readonly int $max
    )
    {
        parent::__construct();
    }

    function refreshData(): static
    {
        $this->data=random_int($this->min,$this->max);
        return $this;
    }

    function reset(): static
    {
        return $this;
    }

    function getBinary(): string
    {
        //pack the value of the integer into a binary string
        return pack("J",$this->data)[0];
    }

    function toHex(): string
    {
        return dechex($this->data);
    }

    function toInt(): int
    {
        return $this->data;
    }

}