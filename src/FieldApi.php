<?php

namespace Foamycastle\UUID;

interface FieldApi
{
    /**
     * Get the value of the provider or the static value supplied at instantiation
     * @param int $padLength the length to which the field's output will be padded with a character
     * @param string $padChar the pad character
     * @return mixed the value of the provider
     */
    function getValue(int $padLength=0,string $padChar='0'): mixed;

    /**
     * Set the field length
     * @param int $fieldLength the desired field length in hexadecimal characters
     */
    function length(int $fieldLength):static;

    /**
     * Perform the operation of apply the UUID version to the field data
     * @param int $version the UUID version
     * @return static
     */
    function applyVersion(int $version):static;

    /**
     * Perform the operation of applying the UUID variant value to the field data
     * @param int $variant the UUID variant value
     * @return static
     */
    function applyVariant(int $variant=2):static;
}