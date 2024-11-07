<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;

abstract class TimeProvider extends Provider implements TimeProviderApi
{
    protected function __construct(ProviderKey $key){
        $this->key = $key;
        $this->register();
    }
    /**
     * @var int Contains the time value expressed as integer
     */
    protected int $time;

    public function getTimeValue(): int
    {
        return $this->time;
    }

}