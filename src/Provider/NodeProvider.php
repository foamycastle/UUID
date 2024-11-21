<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\ProviderApi;
use Stringable;

abstract class NodeProvider extends Provider implements ProvidesHex
{
    protected static array $nodes;
    protected RandomWord $random;
    protected bool $useRandom=false;

}