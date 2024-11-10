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

    public const OFFSET_LEFT=0;
    public const OFFSET_RIGHT=1;

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

    protected int $combinedValue;
    protected int $offsetDirection;

    public function __construct(
        int $bitLength,
        int|Field|Provider $value = 0,
        int $charLength = 0,
        int $bitOffset = 0,
        int $offsetDirection = self::OFFSET_RIGHT,
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
        $this->offsetDirection = $offsetDirection;

        /**
         * If the $value argument is a reference to another field, link to that field's value instead
         */
        if($value instanceof Field) {
            $this->linkedField=$value;
            if($value instanceof FieldInt){
                $value = $value->getValue();
            }
            if($value instanceof FieldHex){
                $value=$value->toInt();
            }
        }

        /**
         * If the $value argument is a reference to a provider, read that provider's data
         */
        if($value instanceof Provider) {
            $this->setProvider($value);
            $value=$this->getValue();
        }
        /**
         * Verify that the value is an integer and has a valid bit-length
         */

        $this->value=$value;

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
            $this->charLength = $this->getHexCharCount($this->getValue() ?? 0);
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
        if($bits == 0) {
            return (PHP_INT_MAX | PHP_INT_MIN);
        }
        return (2 ** $bits) - 1;
    }

    public function combineWith(Field|int $field, int $operation): FieldIntApi
    {
        $fieldValue = match(gettype($field)) {
            'integer'=>$field,
            default=>
                match(get_class($field)) {
                    FieldInt::class=>$field->getAdjustedFieldValue(),
                    FieldHex::class=>$field->getValue()
                }
        };

        $this->combinedValue=match ($operation) {
            self::COMBINE_AND => ($this->getAdjustedFieldValue() & $fieldValue),
            self::COMBINE_OR  => ($this->getAdjustedFieldValue() | $fieldValue),
            self::COMBINE_XOR => ($this->getAdjustedFieldValue() ^ $fieldValue),
            self::COMBINE_NOT => ($this->getAdjustedFieldValue() & ~$fieldValue),
        };
        return new self(
            $this->bitLength,
            $this->combinedValue,
            $this->charLength,
            0
        );
    }

    public function getCombinedValue(): int
    {
        return $this->combinedValue ?? 0;
    }

    /**
     * Return the value of the field after it has been adjusted by the bit offset
     * @return int
     */
    public function getAdjustedFieldValue(): int
    {
        return (
            $this->offsetDirection==self::OFFSET_RIGHT
                ? ($this->getValue() >> $this->bitOffset)
                : ($this->getValue() << $this->bitOffset)
            )
            & $this->maxIntValue($this->bitLength);
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
        if($this->hasLink()){
            $this->value=$this->linkedField->getValue();
        }
        if($this->hasProvider()){
            $this->value=$this->provider->getData();
        }
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
                $this->offsetDirection
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
    public function __toString(): string
    {
        return str_pad(
            dechex(
                $this->getAdjustedFieldValue()
            ),
            $this->charLength,
            '0',
            STR_PAD_LEFT
        );
    }

}