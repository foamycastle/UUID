<?php

namespace Foamycastle\UUID\Provider\TimeProvider;

use Foamycastle\UUID\Provider\ProvidesInt;
use Foamycastle\UUID\Provider\TimeProvider;
use Foamycastle\UUID\ProviderApi;

/**
 * Provides an integer representing the current time in the Gregorian Time Epoch
 */
class GregorianTime extends TimeProvider implements ProvidesInt
{
    /**
     * The number of 100ns periods between the Gregorian Calendar epoch
     * and the Unix time epoch
     */
    private const int TIME_CONSTANT=122192928000000000;
    function refreshData(): static
    {
        //use the unix epoch time value 
        //and add the Gregorian Period
        parent::refreshData();
        $this->data+=self::TIME_CONSTANT;
        return $this;
    }

}