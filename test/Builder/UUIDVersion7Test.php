<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;

class UUIDVersion7Test extends TestCase
{

    public function test__toString()
    {
        $uuid=UUIDBuilder::Version7();
        $this->expectsOutput();
        echo $uuid.PHP_EOL;
    }

}
