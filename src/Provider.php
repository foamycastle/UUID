<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Provider\ProviderKey;

/**
 * @method GregorianTime
 */
abstract class Provider implements ProviderApi
{

    /**
     * Path used to check if a provider class exists
     */
    private const PROVIDER_NS=__NAMESPACE__.'\\Provider\\';

    /**
     * Contains a list of
     * @var array<ProviderKey,Field>
     */
    public static array $activeProviders=[];

    /**
     * The key used to store the instance in the provider array
     * @var ProviderKey 
     */
    protected ProviderKey $key;

    /**
     * Register the child class in the list of active providers
     * @return void
     */
    protected function register():void
    {
        if(!Provider::hasKey($this->key->name)) {
            Provider::Add($this->key, $this);
        }
    }
    protected function unregister():void
    {
        Provider::Remove($this->key);
    }

    abstract public function __invoke(...$args):static;

    public static function hasKey(string $key):bool
    {
        return isset(self::$activeProviders[$key]);
    }

    public static function Add(ProviderKey $key, Provider $provider):void
    {
        self::$activeProviders[$key->name]=$provider;
    }

    public static function Remove(ProviderKey $key):void
    {
        if(self::hasKey($key->name)){
            unset(self::$activeProviders[$key->name]);
        }
    }

    /**
     * Verify that a provider class exists
     * @param string $providerName The name of the provider class
     * @param string $subSpace an optional path within the provider namespace to search for classes. This
     * string must include backslash characters as namespace separators.
     * @return bool TRUE if the provider class can be located and loaded.
     */
    public static function Exists(string $providerName, string $subSpace=''):bool
    {
        if(!empty($subSpace)){
            if(!str_ends_with($subSpace, '\\')){
                $subSpace .='\\';
            }
            if(!str_starts_with($subSpace, '\\')){
                $subSpace ='\\'.$subSpace;
            }
        }
        return class_exists(
            empty($subSpace)
                ? self::PROVIDER_NS.$providerName
                : self::PROVIDER_NS.$subSpace.$providerName
        );
    }


    public static function __callStatic(string $name, array $arguments):Provider|null
    {
        if(self::hasKey($name)){
            return self::$activeProviders[$name](...$arguments);
        }
        if(self::Exists($name)){
            return new (self::PROVIDER_NS.$name)(...$arguments);
        }
        return null;
    }

}