<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

abstract class TimeProvider extends Provider
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

}