<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;

class UUIDVersion3Test extends TestCase
{

    public function test__toString()
    {
        $uuid=UUIDBuilder::Version3('01203103-0313-1230-8832-aadefbacef34','TestingSpace');
        $this->expectsOutput();
        echo "$uuid\n";
    }

}
