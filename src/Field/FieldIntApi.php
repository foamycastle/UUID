<?php

namespace Foamycastle\UUID\Field;

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

    /**
     * Return the value of the field
     * @return int
     */
    function getValue():int;

    /**
     * Return the value of the field after it has been adjusted by the bit offset
     * @return int
     */
    public function getAdjustedFieldValue(): int;

    /**
     * Calculate the combined value of this field and another
     * @param int $operation a class constant that specifies which operation should be performed on the two values
     * @return int|null the result of the operation or null if the operation could not be performed.
     */
    public function getCombinedValue(int $operation): int|null;

    /**
     * Return the maximum decimal value a number of binary bits may represent
     * @param int $bits
     * @return int
     */
    public function maxIntValue(int $bits): int;

}