<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

class RandomProvider extends Provider
{
    public function __construct(private int $bits)
    {
        if($this->bits>63) $this->bits=63;
        if($this->bits<1) $this->bits=1;
        $this->refreshData();
    }

    function refreshData(): ProviderApi
    {
        $this->data = random_int(
            2**($this->bits-1),2**($this->bits)-1
        );
        return $this;
    }

}