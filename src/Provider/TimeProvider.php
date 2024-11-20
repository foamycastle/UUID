<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

abstract class TimeProvider extends Provider implements ProvidesInt
{
    public function refreshData(): ProviderApi
    {
        ['sec'=>$sec,'usec'=>$usec]=gettimeofday();
        $randomUSec=rand(0,9);
        $this->data=
            ($sec*10000000)+
            ($usec*10)+
            $randomUSec;
        return $this;
    }
    function reset(): ProviderApi
    {
        return $this->refreshData();
    }

    function toInt(): int
    {
        return $this->data;
    }
}