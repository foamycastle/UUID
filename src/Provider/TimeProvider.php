<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;

abstract class TimeProvider extends Provider implements TimeProviderApi
{

    /**
     * @var int Contains the time value expressed as integer
     */
    protected int $time;

    protected ProviderKey $key;


    public function getTimeValue(): int
    {
        return $this->time;
    }

}