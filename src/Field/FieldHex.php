<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;

class FieldHex extends Field implements FieldHexApi
{
    public const XFMR_HEX_TO_INT='hexToInt';
    /**
     * Sets the default maximum field length
     */
    public const MAX_FIELD_LEN=16;

    /**
     * The character used to pad output strings
     */
    public const PAD_CHAR='0';

    /**
     * @var bool If TRUE, the output string will be padded with `$padChar` to the length specified in `$charLength`
     */
    protected bool $padOutput;

    /**
     * @var string The string of characters used to pad the output
     */
    protected string $padChar;


    /**
     * @param string $value
     * @param int $charLength
     * @param bool $padOutput
     * @param string $padChar
     */
    public function __construct(
        string $value,
        int $charLength=self::MAX_FIELD_LEN,
        bool $padOutput = false,
        string $padChar = self::PAD_CHAR
    )
    {
        /*
         * validate the characters in the string as hexadecimal.  if the string contains
         * non-hex characters, the value will be blank
         */
        $value = $this->hexStringValidation($value)
            ? $value
            : '';

        /**
         * If the value's length is longer than the specified character length,
         * truncate it in a manner that preserves the LSBs of the hex value
         */
        $value = strlen($value)>$charLength
            ? substr($value, -$charLength)
            : $value;

        //set object properties
        $this->value = $value;
        $this->charLength = $charLength;
        $this->padOutput = $padOutput;
        $this->padChar = $padChar;
    }
    private function hexStringValidation(string $hex):bool
    {
        return preg_match('/^[0-9a-fA-F]+$/i', $hex);
    }
    public function __toString(): string
    {
        /*
         * If the $padchar property is true, perform the padding operation
         */
        $output=$this->padOutput
            ? str_pad($this->value, $this->charLength, self::PAD_CHAR, STR_PAD_LEFT)
            : $this->value;

        return $output;
    }


    function mutateField(?Field $field=null): static
    {
        $field ??= $this->linkedField;
        if($field instanceof FieldIntApi){
            return new self(
                dechex($field->value),
                $field->charLength,
                $this->padOutput,
                $this->padChar
            );
        }
        return $this;
    }

    function pad(bool $pad): static
    {
        $this->padOutput = $pad;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getTransformer(string $transformerKey): callable
    {
        return match($transformerKey) {

            self::XFMR_HEX_TO_INT=>
                function(){
                    return hexdec($this->linkedField->value);
                },
            default=>
                parent::getTransformer(self::XFMR)
        };
    }

}