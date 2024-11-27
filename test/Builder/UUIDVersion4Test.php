<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;

class UUIDVersion4Test extends TestCase
{

    public function test__toString()
    {
        $uuid=UUIDBuilder::Version4();
        $this->expectsOutput();
        echo $uuid.PHP_EOL;
    }

    public function testBatch()
    {
        $uuid=UUIDBuilder::Version4();
        foreach ($uuid->batch(4) as $item) {
            $this->expectsOutput();
            echo $item.PHP_EOL;
        }
    }

}
