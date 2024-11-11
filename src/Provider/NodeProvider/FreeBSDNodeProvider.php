<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;
use Foamycastle\UUID\Provider\ProviderKey;

class FreeBSDNodeProvider extends NodeProvider
{
    public function __construct()
    {
        parent::__construct(ProviderKey::NODE_FREEBSD);
    }

    protected function shellCommand(): string
    {
        return 'netstat -i -f link 2&>1';
    }

}