<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;

class UUIDNilTest extends TestCase
{
    public function test__toString()
    {
        $uuid=UUIDBuilder::Nil();
        $this->assertEquals('00000000-0000-0000-0000-000000000000', (string)$uuid);

    }

}
