<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\ProviderApi;

/**
 * Parent class for Random value providers
 */
abstract class RandomProvider extends Provider
{
    protected function __construct()
    {
        $this->refreshData();
    }

}