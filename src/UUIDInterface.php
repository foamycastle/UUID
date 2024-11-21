<?php

namespace Foamycastle\UUID;

interface UUIDInterface extends \Stringable
{
    function refresh():static;
    function batch(int $count):iterable;
}