<?php

namespace Foamycastle\UUID;

abstract class Provider
{

    abstract public function provide(string $key):mixed;
    abstract public function provideAll(): array;
}