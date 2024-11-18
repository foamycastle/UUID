<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use PHPUnit\Framework\TestCase;

class LinuxNodeProviderTest extends TestCase
{

    public function test__construct()
    {
        $linuxNodeProvider = new LinuxNodeProvider();
        $linuxNodeProvider = new LinuxNodeProvider();
        $this->assertInstanceOf(LinuxNodeProvider::class, $linuxNodeProvider);
    }

    public function testGetFirstNode()
    {
        $linuxNodeProvider = new LinuxNodeProvider();
        $node = $linuxNodeProvider->getFirstNode();
        $this->assertEquals("18:c0:4d:01:5a:9f", $node);
    }

    public function testGetLastNode()
    {
        $linuxNodeProvider = new LinuxNodeProvider();
        $node = $linuxNodeProvider->getLastNode();
        $this->assertEquals("18:c0:4d:01:5a:9f", $node);
    }

    public function testGetAllNodes()
    {
        $linuxNodeProvider = new LinuxNodeProvider();
        $node = $linuxNodeProvider->getAllNodes();
        $this->assertEquals(["18:c0:4d:01:5a:9f"], $node);
    }

    public function testGetAnyNode()
    {
        $linuxNodeProvider = new LinuxNodeProvider();
        $node = $linuxNodeProvider->getAnyNode();
        $this->assertEquals("18:c0:4d:01:5a:9f", $node);
    }

}
