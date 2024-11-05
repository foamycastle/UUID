<?php

namespace Foamycastle\UUID;

interface FieldApi
{
    /**
     * Set a link between two fields
     * @param Field $field
     * @return self
     */
    function setLink(Field $field):static;

    /**
     * Set the data provider property
     * @param Provider $provider
     * @return $this
     */
    function setProvider(Provider $provider):static;

    /**
     * Set the provider key
     * @param string $key The provider key used to access a specific part of a provider's data
     * @return $this
     */
    function setProviderKey(string $key):static;

    /**
     * Pulls data from the provider object using the $providerKey
     * @return $this
     */
    function readProvider():static;

}