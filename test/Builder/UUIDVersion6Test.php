<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;

class UUIDVersion6Test extends TestCase
{

    public function test__toString()
    {
        $uuid=UUIDBuilder::Version6();
        $this->expectsOutput();
        echo $uuid.PHP_EOL;
    }

    public function testBinary()
    {
        $uuid=UUIDBuilder::Version6();
        $this->expectsOutput();
        $output=$uuid->getBinary();
        $this->assertIsString($output);
        $this->assertEquals(16,strlen($output));
        echo $uuid->getBinary();
    }

    public function testBatch()
    {
        $uuid=UUIDBuilder::Version6();
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
