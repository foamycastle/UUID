<?php

namespace Foamycastle\UUID;

use Stringable;

interface Batchable extends Stringable
{

    /**
     * Returns an iterable that delivers multiple UUID strings
     * @param int $count
     * @return iterable
     */
    function batch(int $count):iterable;
}