<?php

namespace Foamycastle\UUID\Provider;

interface CounterProviderApi extends \Stringable
{
    /**
     * Returns the counter's current value
     * @return int
     */
    function getValue():int;
    
    /**
     * Set the counter's maximum value
     * @return static
     */
    function setMin(int $min):static;

    /**
     * Set counter's minimum value
     * @return static
     */
    function setMax(int $max):static;

    /**
     * Set the value by which the counter will increment itself
     * @return static
     */
    function setIncrement(int $increment):static;

    /**
     * Indicates if the counter has reached its maximum value
     * @return bool 
     */
    function atMax():bool;

    /**
     * Returns the counter's value to the minimum value
     * @return static
     */
    function resetCount():static;

}