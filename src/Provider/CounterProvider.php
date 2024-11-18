<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

class CounterProvider extends Provider
{
    public function __construct(
        protected int $min,
        protected int $max,
        protected int $inc=1
    )
    {
        if($this->max<$this->min) $this->max=$this->min+$this->inc;
        if($this->inc>($this->max-$this->min)) $this->inc=($this->max-$this->min);
        parent::__construct();
    }

    function refreshData(): \Foamycastle\UUID\ProviderApi
    {
        if(!isset($this->data)){
            $this->data=$this->min;
            return $this;
        }
        if($this->data == $this->max) {
            $this->data = $this->min;
            return $this;
        }
        if($this->inc > ($this->max-$this->data)) {
            $this->data = $this->max;
            return $this;
        }
        $this->data+=$this->inc;
        return $this;
    }

    public function atMax():bool
    {
        return $this->data==$this->max;
    }

}