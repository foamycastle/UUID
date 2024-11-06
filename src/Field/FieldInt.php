<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\Provider;

class FieldInt extends Field implements FieldIntApi
{


    public const XFMR_HEX_TO_INT='hex2Int';
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
     * @var int $bitOffset A character is composed of 4 bits instead of the traditional octet. If a field's bit length is less than its character length, the bits may be offset. a value of 0 means that all the bits reside in the LSB portion of the word.  A non-zero value pushes the bits toward the MSB.
     */
    protected int $bitOffset;

    public function __construct(
        int $bitLength,
        int $value = 0,
        int $charLength = 0,
        int $bitOffset = 0
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

        /*
         * The bit offset cannot be greater than the bit length
         */
        $bitOffset = min($bitOffset, $bitLength);
        $this->bitOffset = $bitOffset;

        /**
         * Verify that the value, if supplied, has a valid bit-length
         */
        if ($value > 0) {
            if ($this->getBitCount($value) > $bitLength) {
                /*
                 * At this point, the value is too large for the bit-length.  If the $bitOffset property was specified, take the appropriate
                 * number of bits from the value beginning at the offset. If the offset value is 0, simply takes the value's LSBs
                 */
                $value = $value >> $this->bitOffset;
            }

            /**
             * If the value is greater than what the bitLength can express, truncate bits
             */
            if($value>$this->maxIntValue($bitLength)) {
                $value = $this->truncateBits($bitLength,$value);
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
            $this->charLength = $this->getHexCharCount($this->value ?? 0);
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
    }

    public function maxIntValue(int $bits): int
    {
        return (2 ** $bits) - 1;
    }

    public function getCombinedValue(int $operation): int|null
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
        return (int)floor(log($value, 2)-PHP_FLOAT_EPSILON) + 1;
    }
    /**
     * Return the value of the field after it has been adjusted by the bit offset
     * @return int
    */
    public function getAdjustedFieldValue(): int
    {
        return $this->value << $this->bitOffset;
    }/**
     * Returns the number of hexadecimal characters needed to represent a given decimal
     * @param int $value the decimal under test
     * @return int
 */
    protected function getHexCharCount(int $value): int
    {
        return (int)floor(log($value, 16)) + 1;
    }
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Applies a bit mask to a value so that the value has only so many bits
     * @param int $bits the number of bits the value should
     * @param int $value the input value
     * @return int the `$value` after the `$bits` bitmask has been applied
     */
    protected function truncateBits(int $bits, int $value):int
    {
        $bitmask = $bits==64
            ? (PHP_INT_MAX | PHP_INT_MIN)   //all bits on
            : (2**$bits)-1;
        return ($value & $bitmask);
    }

    /**
     * @inheritDoc
     */
    public function mutateField(?Field $field=null): static
    {
        $field ??= $this->linkedField;
        if($field instanceof FieldHexApi) {
            return new self(
                $this->bitLength,
                $this->getTransformer(self::XFMR_HEX_TO_INT)(),
                $this->charLength,
                $this->bitOffset,
                null,
                $this->provider ?? null
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getTransformer(string $transformerKey): callable
    {
        return match ($transformerKey) {

            /**
             * Convert a hex string to an integer
             */
            self::XFMR_HEX_TO_INT=>
            function() {
                return hexdec($this->linkedField->value);
            },

            default=>parent::getTransformer(self::XFMR)
        };

    }

}