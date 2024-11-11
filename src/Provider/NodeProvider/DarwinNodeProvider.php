<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;
use Foamycastle\UUID\Provider\ProviderKey;

class DarwinNodeProvider extends NodeProvider
{
    public function __construct()
    {
        parent::__construct(ProviderKey::NODE_DARWIN);
    }

    protected function shellCommand(): string
    {
        return 'ifconfig 2>&1';
    }

}