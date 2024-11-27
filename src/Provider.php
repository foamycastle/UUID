<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Provider\CounterProvider;
use Foamycastle\UUID\Provider\HashProvider;
use Foamycastle\UUID\Provider\NodeProvider\StaticNodeProvider;
use Foamycastle\UUID\Provider\NodeProvider\SysNodeProvider;
use Foamycastle\UUID\Provider\RandomProvider\RandomHex;
use Foamycastle\UUID\Provider\RandomProvider\RandomInt;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\Provider\TimeProvider\GregorianTime;
use Foamycastle\UUID\Provider\TimeProvider\UnixTime;

abstract class Provider implements ProviderApi
{
    protected mixed $data=null;

    /**
     * Trigger a data refresh of the specified provider objects
     * @param ProviderApi[] $providers
     * @return void
     */
    public static function Refresh(...$providers):void
    {
        $providers=array_values($providers);
        foreach ($providers as $provider) {
            $provider->refreshData();
        }
    }

    /**
     * Trigger a reset of the specified provider objects
     * @param ProviderApi[] $providers
     * @return void
     */
    public static function ResetProvider(...$providers):void
    {
        $providers=array_values($providers);
        foreach ($providers as $provider) {
            $provider->reset();
        }
    }
    public static function RandomInt(int $min,$max):RandomInt
    {
        return new RandomInt($min,$max);
    }

    public static function RandomWord(int $bits):RandomWord
    {
        return new RandomWord($bits);
    }

    public static function RandomHex(int $hexLength):RandomHex
    {
        return new RandomHex($hexLength);
    }

    public static function Counter(int $min,int $max,int $inc=1):CounterProvider
    {
        return new CounterProvider($min,$max,$inc);
    }

    public static function GregorianTime():GregorianTime
    {
        return new GregorianTime();
    }

    public static function UnixTime():UnixTime
    {
        return new UnixTime();
    }

    public static function HashMD5(string $namespace, string $name):HashProvider
    {
        return new HashProvider($namespace,$name,3);
    }

    public static function HashSHA1(string $namespace, string $name):HashProvider
    {
        return new HashProvider($namespace,$name,3);
    }

    public static function SystemNode():SysNodeProvider
    {
        return new SysNodeProvider();
    }

    public static function StaticNode(string $node):StaticNodeProvider
    {
        return new StaticNodeProvider($node);
    }
}