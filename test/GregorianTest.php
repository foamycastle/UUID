<?php

namespace Foamycastle\UUID\Provider\TimeProvider;

use PHPUnit\Framework\TestCase;

class GregorianTest extends TestCase
{

    public function testRefreshData()
    {
        $time=new GregorianTime();
        $this->expectsOutput();
        echo $time->getData().PHP_EOL;
    }

}
