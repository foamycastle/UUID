<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

abstract class RandomProvider extends Provider
{
    protected function __construct()
    {
        parent::__construct();
    }

}