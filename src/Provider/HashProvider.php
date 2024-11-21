<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

/**
 * Provides either a MD5 hash string or a SHA1 hash string to fields
 * for use in UUID strings
 * @author Aaron Sollman <unclepong@gmail.com>
 */
class HashProvider extends Provider implements ProvidesBinary, ProvidesHex
{
    public function __construct(
        private readonly string $namespace,
        private readonly string $key,
        private readonly int $version
    )
    {

    }

    function refreshData(): static
    {
        $this->data=match ($this->version){
            3=>md5($this->namespace.$this->key,true),
            5=>sha1($this->namespace.$this->key,true)
        };
        return $this;
    }

    function getBinary(): string
    {
        return substr($this->data ?? $this->refreshData()->data,0,16);
    }

    function reset(): static
    {
        return $this;
    }

    function toHex(): string
    {
        return substr(bin2hex($this->getBinary()),0,32);
    }

}