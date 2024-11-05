<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Field;

class FieldInt extends Field implements FieldIntApi
{

    public const COMBINE_NOT = 3;
    public const COMBINE_AND = 1;
    public const COMBINE_OR = 0;
    public const COMBINE_XOR = 2;

    /**
     * @var int<1,64> $bitLength the length of the field in bits.  Upon field construction, the field value is compared against the
     * specified bit-length.  if the value is larger than the bit-length, the value's bits are truncated to either the LSBs
     * or, if the `$offset` is set to a valid value, the value's bit started at the offset are used.
     */
    protected int $bitLength;
    /**
     * @var int This property contains the value of this field combined with an arbitrary outside value
     */
    protected int $combinedValue;
    /**
     * If this field is linked to another one, this property will contain the combined values of the two fields.
     * @var int  $linkedValue;
     */
    protected int $linkedValue;
    /**
     * @var int $bitOffset A character is composed of 4 bits instead of the traditional octet. If a field's bit length is less than its character length, the bits may be offset. a value of 0 means that all the bits reside in the LSB portion of the word.  A non-zero value pushes the bits toward the MSB.
     */
    protected int $bitOffset;

    protected function __construct(
        int $bitLength,
        int $value = -1,
        int $charLength = 0,
        int $bitOffset = 0,
        ?Field $linkedField = null,
        ?Provider $provider = null,
        ?string $providerKey = null
    ) {
        /* To begin, all that needs to be defined is bit-length of the field. The maximum bit-length is 64bits.
         * Validate that now. If the supplied value is outside the valid bounds, place it at the nearest boundary.
         */
        if ($bitLength < 1) {
            $bitLength = 1;
        }
        if ($bitLength > 64) {
            $bitLength = 64;
        }

        $this->bitLength = $bitLength;
        $this->bitOffset = $bitOffset;
        /**
         * Verify that the value, if supplied, has a valid bit-length
         */
        if ($value > -1) {
            if ($this->getBitCount($value) > $bitLength) {
                /*
                 * At this point, the value is too large for the bit-length.  If the $bitOffset property was specified, take the appropriate
                 * number of bits from the value beginning at the offset. If the offset value is 0, simply takes the value's LSBs
                 */
                $value = $value >> $bitOffset;
            }
            //Assign the value to the property
            $this->value = $value;
        }

        /*
         * Make sure the character length, if specified, can accommodate the value when the field is rendered to a string
         */
        if ($charLength != 0) {
            if ($charLength < 0) {
                $charLength = 1;
            }
            if ($charLength > 16) {
                $charLength = 16;
            }
            $this->charLength = $charLength;
        } else {
            /**
             * $charLength wasn't specified, so it must be inferred from the value.
             */
            $this->charLength = $this->getHexCharCount($value ?? 0);
        }

        /**
         * If the provider object is specified, a provider key must be specified. If a provider key is not specified,
         * don't assign the provider object
         */
        if ($providerKey !== null) {
            $this->provider    = $provider;
            $this->providerKey = $providerKey;
        }

        /*
         * If the linked field argument is passed, make sure that referenced field has the same bit-length as this one.
         * Otherwise, don't assign the linked field property
         */
        if ($linkedField !== null) {
            if ($linkedField->bitLength == $this->bitLength) {
                $this->linkedField = $linkedField;
            }
        }
    }

    public function getBitLength(): int
    {
        return $this->bitLength;
    }

    public function hasBitOffset(): bool
    {
        return $this->bitOffset > 0;
    }

    public function getBitOffset(): int
    {
        return $this->bitOffset;
    }/**
     * Return the maximum decimal value a number of binary bits may represent
     * @param int $bits
     * @return int
 */
    protected function maxIntValue(int $bits): int
    {
        return (2 ** ($this->getBitCount($bits))) - 1;
    }/**
     * Calculate the combined value of this field and another
     * @param int $operation a class constant that specifies which operation should be performed on the two values
     * @return int|null the result of the operation or null if the operation could not be performed.
 */
    protected function getCombinedValue(int $operation): int|null
    {

        //if the linkedField property is not set, return null
        $field = $field ?? $this->linkedField;
        if (!isset($field)) {
            return null;
        }

        /*
         * if the referenced field has a bit-offset specified, copy and adjust the referenced field's value
         */
        $fieldValue = $field->bitOffset != 0
            ? $field->getAdjustedFieldValue()
            : $field->value;

        return match ($operation) {
            self::COMBINE_AND => ($this->value & $fieldValue),
            self::COMBINE_OR  => ($this->value | $fieldValue),
            self::COMBINE_XOR => ($this->value ^ $fieldValue),
            self::COMBINE_NOT => ($this->value & ~$fieldValue),
        };
    }/**
     * Returns the number of bits needed to represent a given decimal
     * @param int $value the integer under test
     * @return int the number of bits needed to represent `$value`
 */
    protected function getBitCount(int $value): int
    {
        return floor(log($value, 2)) + 1;
    }/**
     * Return the value of the field after it has been adjusted by the bit offset
     * @return int
 */
    protected function getAdjustedFieldValue(): int
    {
        return $this->value << $this->bitOffset;
    }/**
     * Returns the number of hexadecimal characters needed to represent a given decimal
     * @param int $value the decimal under test
     * @return int
 */
    protected function getHexCharCount(int $value): int
    {
        return floor(log($value, 16)) + 1;
    }

}