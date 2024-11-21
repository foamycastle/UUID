<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;

/**
 * Parent class for all time providers
 * @author Aaron Sollman <unclepong@gmail.com>
 */
abstract class TimeProvider extends Provider implements ProvidesInt
{
    public function __construct()
    {
    }

    public function refreshData(): static
    {
        //read the system time. system time is on the order of microseconds
        //this should be converted to 100-nanosecond intervals
        //since the 100ns time interval is unavailable, randomize it
        ['sec'=>$sec,'usec'=>$usec]=gettimeofday();
        $randomNS=rand(0,9);
        $this->data=
            ($sec*10000000)+    //seconds
            ($usec*10)+         //microseconds
            $randomNS;          //random sub-microsecond
        return $this;
    }
    function reset(): static
    {
        return $this->refreshData();
    }

    function toInt(): int
    {
        return $this->data ?? $this->reset()->toInt();
    }
}