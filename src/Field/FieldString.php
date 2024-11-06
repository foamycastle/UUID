<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;

class FieldString extends Field implements FieldStringApi
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

    public function __construct(
        string $value,
        int $charLength=self::MAX_FIELD_LEN,
        bool $padOutput = false,
        string $padChar = self::PAD_CHAR
    )
    {
        //set object properties
        $this->value = $value;
        $this->charLength = $charLength;
        $this->padOutput = $padOutput;
        $this->padChar = $padChar;
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
            }
        };
    }

}