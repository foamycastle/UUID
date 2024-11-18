<?php

namespace Foamycastle\UUID\Provider;

use PHPUnit\Framework\TestCase;

class CounterProviderTest extends TestCase
{

    public function test__construct()
    {
        $counter=new CounterProvider(0,9,1);
        $this->assertEquals(0,$counter->getData());
    }

    public function testAtMax()
    {
        $counter=new CounterProvider(0,1,1);
        $this->assertEquals(0,$counter->getData());
        $this->assertFalse($counter->atMax());
        $this->assertEquals(1,$counter->refreshData()->getData());
        $this->assertTrue($counter->atMax());
    }

    public function testRefreshData()
    {
        $counter=new CounterProvider(0,2,1);
        $counter->refreshData()->refreshData();
        $this->assertTrue($counter->atMax());
        $counter->refreshData();
        $this->assertEquals(0,$counter->getData());
    }

}
