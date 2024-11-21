<?php

namespace Foamycastle\UUID;

interface ProviderApi
{
    /**
     * Trigger a refresh of the provider data
     * @return static
     */
    function refreshData():static;

    /**
     * Trigger a state reset of the provider object
     * @return static
     */
    function reset():static;

}