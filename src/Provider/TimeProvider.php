<?php

namespace Foamycastle\UUID\Provider;

use Cassandra\Time;
use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

abstract class TimeProvider extends Provider implements TimeProviderApi
{

    /**
     * @var int Contains the time value expressed as integer
     */
    protected int $time;

    public function getTimeValue(): int
    {
        return $this->time;
    }

}