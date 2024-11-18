<?php

namespace Foamycastle\UUID\Provider;

use PHPUnit\Framework\TestCase;

class RandomProviderTest extends TestCase
{

    public function test__invoke()
    {
        $testInvoke=new RandomProvider(60);

        $testInvokeAgain=$testInvoke(100);
        $this->assertInstanceOf(RandomProvider::class, $testInvokeAgain);
        $this->assertGreaterThanOrEqual(0,$testInvokeAgain->getData());
        $this->assertLessThanOrEqual(1000000,$testInvokeAgain->getData());
    }

    public function testRandomGen()
    {
        $rando=new RandomProvider(100);
        $counter=0;
        $getData=$rando->getData();
        while($counter++<10) {
            $this->assertIsInt($getData);
            $this->expectsOutput();
            echo $getData.PHP_EOL;
            $this->assertEquals($getData,$rando->getData());
            $rando->refreshData();
            $this->assertNotEquals($getData,$rando->getData());
            $getData = $rando->getData();
        }

    }

}
