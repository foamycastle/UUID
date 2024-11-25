<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;

class UUIDMaxTest extends TestCase
{

    public function test__toString()
    {
        $uuid=UUIDBuilder::Max();
        $this->assertEquals('FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF', (string)$uuid);
    }

}
