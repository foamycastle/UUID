<?php

namespace Foamycastle\UUID;

interface FieldApi extends \Stringable
{

    /**
     * Reset the value of the field to `$value`
     * @param mixed $value
     * @return FieldApi
     */
    function __invoke(int|string|FieldApi $value): FieldApi;

    /**
     * Bitwise OR operation
     * @param int|Field $value
     * @return FieldApi
     */
    function orValue(int|Field $value): FieldApi;

    /**
     * Bitwise AND operation
     * @param int|Field $value
     * @return FieldApi
     */
    function andValue(int|Field $value): FieldApi;

    /**
     * Shift the bits of a value right
     * @param int $bits
     * @return FieldApi
     */
    function shiftRight(int $bits): FieldApi;

    /**
     * Shift the bits of a value left
     * @param int $bits
     * @return FieldApi
     */
    function shiftLeft(int $bits): FieldApi;

    /**
     * Export the field's value as an integer
     * @return int
     */
    function toInt():int;

    /**
     * Export the field's value as a hex string
     * @return string
     */
    function toHex():string;

    /**
     * Triggers an update of the provider data
     * @return void
     */
    function refreshProvider():void;

    /**
     * Create a new instance of field from a primitive value
     * @param mixed $value
     * @return FieldApi
     */
    static function From(mixed $value):?FieldApi;

    /**
     * Create a new instance of field from the value of a provider
     * @param ProviderApi $provider
     * @param bool $refresh
     * @return FieldApi
     */
    static function FromProvider(ProviderApi $provider, bool $refresh=false):?FieldApi;


}