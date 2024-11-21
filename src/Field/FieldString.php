<?php

namespace Foamycastle\UUID\Field;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\FieldApi;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\ProviderApi;

class FieldString extends Field implements FieldStringApi
{
    private int $start;
    protected function __construct(ProviderApi|ProvidesHex $provider)
    {
        parent::__construct();
        $this->setProvider($provider);
    }

    public function getValue(int $padLength=0, string $padChar = '0'): mixed
    {
        $outputValue = isset($this->start)
            ? substr($this->provider->toHex(),$this->start,$this->fieldLength)
            : substr($this->provider->toHex(),0,$this->fieldLength);

        if($this->fieldLength!=0){
            if(strlen($outputValue)<$this->fieldLength) {
                $outputValue = str_pad($outputValue, $this->fieldLength, $padChar, STR_PAD_LEFT);
            }
            if(strlen($outputValue)>$this->fieldLength){
                $outputValue = substr($outputValue,0,$this->fieldLength);
            }
        }
        return $outputValue;
    }

    function applyVersion(int $version): static
    {
        $this->appendProcessQueue(
            function(string $value) use($version){
                return "$version".substr($value,1);
            }
        );
        return $this;
    }

    function applyVariant(int $variant = 2): static
    {
        $this->appendProcessQueue(
            function (string $value) use ($variant){
                $charValue=hexdec($value[0]);
                $variantChar=dechex($variant | $charValue);
                return $variantChar.substr($value,1);
            }
        );
        return $this;
    }

    function startAt(int $start): FieldStringApi&FieldApi
    {
        $this->start=$start;
        return $this;
    }

    public function __toString(): string
    {
        return $this->processQueue();
    }

}