<?php

namespace Foamycastle\UUID\Provider;

use PHPUnit\Framework\TestCase;

class RandomProviderTest extends TestCase
{

    public function test__construct()
    {
        $random=new RandomProvider(16);
        $this->assertGreaterThanOrEqual(2**15,$random->getData());
        $this->assertLessThanOrEqual((2**16)-1,$random->getData());
    }

}
