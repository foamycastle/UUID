<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Field\FieldHex;
use Foamycastle\UUID\Provider\NodeProviderApi;

abstract class Field implements FieldErrorInterface, FieldApi
{

    /**
     * Store error messages
     * @var array<array{0:int,1:string}>
     */
    protected array $errorStack;

    /**
     * The field's current value
     * @var mixed
     */
    protected mixed $value;

    protected int $bitLength = 0;
    protected int $hexStringLength = 0;

    protected ProviderApi $provider;

    static function From(mixed $value): ?FieldApi
    {
        if (
            !(
                $value instanceof FieldApi ||
                is_string($value) ||
                is_int($value)
            )) {
            return null;
        }

        return new static($value);
    }

    static function FromProvider(ProviderApi $provider, bool $refresh=false): FieldApi
    {
        if($provider instanceof NodeProviderApi) {
            $data=$provider->getFirstNode();
        }else{
            $data=$provider->getData();
        }
        $newField=new static($data);
        $newField->provider=$provider;
        if($refresh){
            $newField->provider->refreshData();
        }
        return $newField;
    }

    function refreshProvider(): void
    {
        $this->provider->refreshData();
    }


    function newError(int $code, string $message): void
    {
        $this->errorStack[] = [$code,$message];
    }

    function hasError(): bool
    {
        return count($this->errorStack ?? [])>0;
    }

    function getLastError(): array
    {
        return end($this->errorStack);
    }

    function getLastErrorMessage(): string
    {
        [$code,$message] = $this->getLastError();
        return $message ?? '';
    }

    function getLastErrorNumber(): int
    {
        [$code,$message] = $this->getLastError();
        return $code ?? -1;
    }

    public function __toString(): string
    {
        return $this->toHex();
    }

    function orValue(int|Field $value): FieldApi
    {
        $field=($value instanceof Field) ? $value->value : $value;
        $thisValue=$this->toInt();
        return new static(($field|$thisValue));
    }

    function andValue(int|Field $value): FieldApi
    {
        $field=($value instanceof Field) ? $value->value : $value;
        $thisValue=$this->toInt();
        return new static(($field&$thisValue));
    }

    function shiftRight(int $bits): FieldApi
    {
        $thisValue=$this->toInt();
        return new static($thisValue>>($bits));
    }

    function shiftLeft(int $bits): FieldApi
    {
        $thisValue=$this->toInt();
        return new static($thisValue<<($bits));
    }

    function toHex(): string
    {
        return substr(unpack('H*', $this->value)[1], -$this->hexStringLength);
    }

    function toInt(): int
    {
        return unpack('J', $this->value)[1];
    }


}