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
     * Removes the link to the referenced field
     * @return $this
     */
    function unsetLink():static;

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

    /**
     * Import a value from another field and transform it.  If the field link property has been set, that field value is read and assumed into this field
     * @param Field|null $field If supplied, this field's value will be read and assumed into this field
     * @return $this
     */
    function mutateField(?Field $field=null):static;

    /**
     * Sets the character length of the string output
     * @param int $length
     * @return $this
     */
    function setCharLength(int $length):static;


}