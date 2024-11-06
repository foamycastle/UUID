<?php

namespace Foamycastle\UUID;

interface ProviderApi
{

    /**
     * Return data from the provider engine
     * @param string $key specifies which piece of data will be provided
     * @return mixed the data
     */
    public function getData(string $key):mixed;

    /**
     * Perform the logic of updating the provider's data
     * @return self
     */
    public function refreshData(): self;
}