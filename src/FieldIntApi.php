<?php

namespace Foamycastle\UUID;

interface FieldIntApi
{

    /**
     * @return bool TRUE if the field has a bit-offset property set
     */
    function hasBitOffset():bool;

    /**
     * @return int Return the value of the bit offset property
     */
    function getBitOffset():int;

    /**
     * @return int Return the bit length of the field
     */
    function getBitLength():int;

}