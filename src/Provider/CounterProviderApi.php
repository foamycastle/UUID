<?php

namespace Foamycastle\UUID\Provider;

interface CounterProviderApi extends \Stringable
{
    function getValue():int;
    function setMin(int $min):static;
    function setMax(int $max):static;
    function setIncrement(int $increment):static;
    function atMax():bool;
    function resetCount():static;

}