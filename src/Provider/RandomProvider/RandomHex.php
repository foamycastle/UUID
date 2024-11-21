<?php

namespace Foamycastle\UUID\Provider\RandomProvider;

use Foamycastle\UUID\Provider\ProvidesBinary;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\Provider\RandomProvider;
use Foamycastle\UUID\ProviderApi;

class RandomHex extends RandomProvider implements ProvidesHex, ProvidesBinary
{
    public function __construct(private readonly int $hexLength)
    {
        parent::__construct();
    }

    function refreshData(): static
    {
        $randomLength=intval($this->hexLength/2) + (($this->hexLength % 2)==1);
        $this->data = random_bytes($randomLength);
        return $this;
    }

    function reset(): static
    {
        return $this->refreshData();
    }

    function getBinary(): string
    {
        return $this->data;
    }

    function toHex(): string
    {
        return bin2hex($this->data);
    }


}