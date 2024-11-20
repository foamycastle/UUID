<?php

namespace Foamycastle\UUID\Provider\RandomProvider;

use Foamycastle\UUID\Provider\ProvidesBinary;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\Provider\RandomProvider;
use Foamycastle\UUID\ProviderApi;

class RandomHex extends RandomProvider implements ProvidesHex, ProvidesBinary
{
    protected function __construct(private readonly int $hexLength)
    {
        parent::__construct();
    }

    function refreshData(): \Foamycastle\UUID\ProviderApi
    {
        $randomLength=intval($this->hexLength/2) + (($this->hexLength % 2)==1);
        $this->data = random_bytes($randomLength);
        return $this;
    }

    function reset(): \Foamycastle\UUID\ProviderApi
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