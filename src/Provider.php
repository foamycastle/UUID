<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Provider\ProviderKey;

abstract class Provider implements ProviderApi
{

    /**
     * Used to
     */
    private const PROVIDER_NS=__NAMESPACE__.'\\Provider\\';

    /**
     * Contains a list of
     * @var array<ProviderKey,Field>
     */
    public static array $activeProviders=[];

    /**
     * Register the child class in the list of active providers
     * @return void
     */
    protected function register():void
    {
        if(!Provider::HasKey($this->key->name)) {
            Provider::Add($this->key, $this);
        }
    }
    protected function unregister():void
    {
        Provider::Remove($this->key);
    }

    abstract public function __invoke(...$args):static;

    public static function HasKey(string $key):bool
    {
        return isset(self::$activeProviders[$key]);
    }

    public static function Add(ProviderKey $key, Provider $provider):void
    {
        self::$activeProviders[$key->name]=$provider;
    }

    public static function Remove(ProviderKey $key):void
    {
        if(self::HasKey($key->name)){
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
        if(self::HasKey($name)){
            return self::$activeProviders[$name](...$arguments);
        }
        if(self::Exists($name)){
            return new (self::PROVIDER_NS.$name)(...$arguments);
        }
        return null;
    }

}