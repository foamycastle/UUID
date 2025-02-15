<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\FieldApi;
use Foamycastle\UUID\Provider\ProvidesInt;
use Foamycastle\UUID\ProviderApi;

/**
 * A field object that reads an integer value from provider and transforms it into
 * a value usable in a UUID string
 * @author Aaron Sollman <unclepong@gmail.com>
 */
class FieldInt extends Field implements FieldIntApi
{
    protected function __construct(ProviderApi|ProvidesInt $provider)
    {
        parent::__construct();
        $this->setProvider($provider);
    }

    public function getValue(int $padLength=0, string $padChar = '0'): int
    {
        return $this->provider->toInt();
    }

    /**
     * Return the value of the field in hexadecimal characters to the specified length
     * of the $fieldLength property
     * @param int $padLength
     * @param string $padChar
     * @return string The formatted field value
     */
    protected function toHex(int $padLength=0, string $padChar = '0'): string
    {
        $outputValue = dechex($this->processQueue());
        return $padLength==0
            ? str_pad($outputValue,$padLength,$padChar)
            : $outputValue;
    }

    function applyVersion(int $version): static
    {
        $this->appendProcessQueue(
            function (int $value) use($version){
                return $value|($version<<12);
            }
        );
        return $this;
    }

    function applyVariant(int $variant = 2): static
    {
        $this->appendProcessQueue(
            function (int $value) use($variant){
                $bitsNeeded=(int)floor(log($variant,2))+1;
                $charValue=$value & ((2**(16-$bitsNeeded))-1);
                return ($variant<<(16-$bitsNeeded)) | $charValue;
            }
        );
        return $this;
    }

    function shiftLeft(int $bits): FieldApi&FieldIntApi
    {
        $this->appendProcessQueue(
            function (int $value) use($bits){
                return ($value<<$bits);
            }
        );
        return $this;
    }

    function shiftRight(int $bits): FieldApi&FieldIntApi
    {
        $this->appendProcessQueue(
            function (int $value) use($bits){
                return ($value>>$bits);
            }
        );
        return $this;
    }

    function bitMask(int $input): FieldApi&FieldIntApi
    {
        $this->appendProcessQueue(
            function (int $value) use($input){
                return ($value&$input);
            }
        );
        return $this;
    }

    public function __toString(): string
    {
        $outputValue=$this->toHex($this->fieldLength ?? 0);
        if($this->fieldLength!=0){
            if(strlen($outputValue)<$this->fieldLength) {
                $outputValue = str_pad($outputValue, $this->fieldLength, '0', STR_PAD_LEFT);
            }
            if(strlen($outputValue)>$this->fieldLength){
                $outputValue = substr($outputValue,0,$this->fieldLength);
            }
        }
        return $outputValue;
    }

}