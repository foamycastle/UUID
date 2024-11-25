<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;

class NilMax extends Provider implements ProvidesBinary,ProvidesHex
{
    public function __construct(bool $nilUUID=false)
    {
        $this->data=
            str_pad(
                $this->data,
                32,
                ($nilUUID ? '0' : 'F')
            );
    }

    /**
     * @inheritDoc
     */
    function refreshData(): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    function reset(): static
    {
        return $this;
    }

    function getBinary(): string
    {
        return hex2bin($this->data);
    }

    function toHex(): string
    {
        return $this->data;
    }

}