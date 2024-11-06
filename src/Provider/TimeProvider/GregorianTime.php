<?php

namespace Foamycastle\UUID\Provider\TimeProvider;

use Foamycastle\UUID\Provider\TimeProvider;

class GregorianTime extends TimeProvider
{
    private const TIME_CONSTANT=122192928000000000;
    /**
     * @inheritDoc
     */
    public function getData(string $key): mixed
    {
        return $this->getTimeValue();
    }

    /**
     * @inheritDoc
     */
    public function refreshData(): static
    {
        ['sec'=>$sec,'usec'=>$usec]=gettimeofday();
        static $nsec=-1;
        $this->time=
            self::TIME_CONSTANT+    //Gregorian time constant
            ($sec*10000000)+        //Unix seconds converted to sub-µsec
            ($usec*10)+             //Unix µseconds converted to sub-µsec
            (++$nsec==10 ? $nsec=0:$nsec);            //Sequential 100-nano second value
        return $this;
    }

}