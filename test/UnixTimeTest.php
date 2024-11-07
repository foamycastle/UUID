<?php

namespace Foamycastle\UUID\Provider;

use PHPUnit\Framework\TestCase;

class UnixTimeTest extends TestCase
{

    public function test__invoke()
    {
        $unix=new UnixTime();
        $newUnix=$unix();
        $this->assertInstanceOf(UnixTime::class, $newUnix);
    }

    public function testGetData()
    {
        $unix=new UnixTime();
        $time=$unix->getData();
        $this->assertIsInt($time);
        $this->expectsOutput();
        echo $time;
    }

    public function test__construct()
    {

    }

    public function testRefreshData()
    {
        $unix=new UnixTime();
        $time=$unix->getData();
        $newData=$unix->refreshData()->getData();
        $this->assertNotEquals($time, $newData);
        $this->expectsOutput();
        echo "$time\n$newData\n";
    }

}
