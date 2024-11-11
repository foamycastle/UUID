<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;
use Foamycastle\UUID\Provider\ProviderKey;

class LinuxNodeProvider extends NodeProvider
{
    public function __construct()
    {
        parent::__construct(ProviderKey::NODE_LINUX);
    }

    protected function shellCommand(): string
    {
        return 'netstat -ie 2>&1';
    }

}