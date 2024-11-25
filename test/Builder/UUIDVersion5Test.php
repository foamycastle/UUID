<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\UUIDBuilder;
use PHPUnit\Framework\TestCase;

class UUIDVersion5Test extends TestCase
{

    public function test__toString()
    {
        $uuid=UUIDBuilder::Version5('02318974-AD88-42EF-B110-F51EA213FD45','Gerb');
        $this->expectsOutput();
        echo $uuid.PHP_EOL;
    }

}
