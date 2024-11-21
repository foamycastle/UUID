<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\ProviderApi;
use Stringable;

/**
 * Parent class for all node providers
 * @author Aaron Sollman <unclepong@gmail.com>
 */
abstract class NodeProvider extends Provider implements ProvidesHex
{
    /**
     *@var array an array of nodes either retrieved from the system or randomly generated
     */
    protected static array $nodes;

    /**
     * @var bool a flag indicating that a provider should use a random node value by default
     */
    protected bool $useRandom=false;
    
    /**
     * @var RandomWord a random node provider
     */
    protected RandomWord $random;
    
}