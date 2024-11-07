<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;

class GregorianTime extends TimeProvider
{
    private const TIME_CONSTANT=122192928000000000;
    /**
     * @inheritDoc
     */
    public function __construct(public readonly int $version=1)
    {
        $this->key = $this->version==1
            ? ProviderKey::GREGOR_V1
            : ProviderKey::GREGOR_V6;
        if(!Provider::HasKey($this->key->name)) {
            $this->register();
        }
        $this->refreshData();
    }

    /**
     * @param $args array{0:int<1|6>}
     * @return $this
     */
    public function __invoke(...$args): static
    {
        return new self();
    }

    public function getData(): int
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