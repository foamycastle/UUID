<?php

namespace Foamycastle\UUID;

interface FieldApi extends \Stringable
{

    /**
     * Return the value contained in the class as an integer
     * @return int
     */
    function getValue():int;
    /**
     * Indicates whether there is an active link with another field's value
     * @return bool
     */
    function hasLink():bool;
    /**
     * Set a link between two fields
     * @param Field $field
     * @return self
     */
    function setLink(Field $field):static;

    /**
     * Returns the reference to the linked field
     * @return Field|null
     */
    function getLink():?Field;

    /**
     * Removes the link to the referenced field
     * @return $this
     */
    function unsetLink():static;

    /**
     * Indicates the provider property has been set
     * @return bool
     */
    function hasProvider():bool;

    /**
     * Returns the reference to the provider instance
     * @return Provider|null
     */
    function getProvider():?Provider;

    /**
     * Set the data provider instance
     * @param Provider $provider
     * @return $this
     */
    function setProvider(Provider $provider):static;

    /**
     * Removes the reference to the data provider
     * @return $this
     */
    function unsetProvider():static;

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