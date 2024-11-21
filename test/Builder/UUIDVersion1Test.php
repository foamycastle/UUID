<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UUIDVersion1Test extends TestCase
{

    public function test__construct()
    {
        $uuid=UUIDBuilder::Version1();
        $this->expectsOutput();
        echo $uuid.PHP_EOL;
    }
}
