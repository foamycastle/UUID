<?php

namespace Foamycastle\UUID\Provider\TimeProvider;

use Foamycastle\UUID\Provider\ProvidesInt;
use Foamycastle\UUID\Provider\TimeProvider;
use Foamycastle\UUID\ProviderApi;

class GregorianTime extends TimeProvider implements ProvidesInt
{
    private const int TIME_CONSTANT=122192928000000000;
    function refreshData(): static
    {
        parent::refreshData();
        $this->data+=self::TIME_CONSTANT;
        return $this;
    }

}