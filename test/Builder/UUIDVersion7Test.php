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
    public function testBatch()
    {
        $uuid=UUIDBuilder::Version7();
        foreach ($uuid->batch(10) as $item) {
            $this->expectsOutput();
            echo $item.PHP_EOL;
        }
        foreach ($uuid->batch(10) as $item) {
            $this->expectsOutput();
            echo $item.PHP_EOL;
        }
    }

}
