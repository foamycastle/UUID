<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;

class LinuxNodeProvider extends NodeProvider
{

    protected function shellCommand(): string
    {
        return 'netstat -ie 2>&1';
    }

}