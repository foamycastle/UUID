<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;

class WinNodeProvider extends NodeProvider
{
    protected function shellCommand(): string
    {
        return 'ipconfig /all 2>&1';
    }

}