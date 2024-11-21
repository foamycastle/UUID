<?php

namespace Foamycastle\UUID\Provider;

/**
 * Public API for providers that provide binary strings
 * @author Aaron Sollman <unclepong@gmail.com>
 */
interface ProvidesBinary
{
    /**
     * @return string a binary string
     */
    function getBinary():string;
}