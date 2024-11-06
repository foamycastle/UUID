<?php

namespace Foamycastle\UUID;

abstract class Provider implements ProviderApi
{

    /**
     * Used to
     */
    private const PROVIDER_NS=__NAMESPACE__.'\\Provider\\';
    /**
     * Contains a list of
     * @var array<string,Field>
     */
    public static array $keys=[];
    public static function hasKey(string $key):bool
    {
        return isset(self::$keys[$key]);
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
}