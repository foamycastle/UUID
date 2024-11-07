<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Provider\ProviderKey;

interface ProviderApi
{

    /**
     * Return data from the provider engine
     * @return mixed the data
     */
    public function getData():mixed;

    /**
     * Perform the logic of updating the provider's data
     * @return static
     */
    public function refreshData(): static;

    /**
     * Verifies that the current list of loaded providers contains a provider known by a key
     * @param string $key
     * @return bool
     */
    public static function hasKey(string $key):bool;
}