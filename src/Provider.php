<?php

namespace Foamycastle\UUID;

abstract class Provider implements ProviderApi
{
    protected mixed $data=null;
    public function __construct()
    {
        $this->refreshData();
    }

    public function getData(): mixed
    {
        return $this->data;
    }

}