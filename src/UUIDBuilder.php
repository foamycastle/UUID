<?php

namespace Foamycastle\UUID;

use Exception;
use Foamycastle\UUID\Builder\UUIDVersion1;
use Foamycastle\UUID\Builder\UUIDVersion3;
use Foamycastle\UUID\Builder\UUIDVersion4;
use Foamycastle\UUID\Field\FieldIntApi;
use Foamycastle\UUID\Field\FieldKey;
use Foamycastle\UUID\Field\FieldStringApi;

use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\Provider\ProvidesBinary;

use Stringable;

abstract class UUIDBuilder implements ProvidesBinary, Stringable
{
    protected const string FORMAT='%1$s-%2$s-%3$s-%4$s-%5$s';

    /**
     * @var array The data providers for the UUID builder
     */
    protected array $providers;
    /**
     * @var string The binary representation of the UUID string
     */
    protected string $binOutput;

    /**
     * @var array The fields that comprise the UUID string
     */
    protected array $fields;
    protected function __construct(
        protected int $version
    )
    {
    }

    protected function registerProvider(ProviderKey $key, ...$args):Provider
    {
        return $this->providers[$key->name]=new $key->value(...$args);
    }
    protected function provider(ProviderKey $key):Provider
    {
        return $this->providers[$key->name];
    }
    protected function registerField(FieldKey $name, ProviderKey $provider, ...$args):(FieldApi&FieldStringApi)|(FieldApi&FieldIntApi)
    {
        return $this->fields[$name->value]=Field::FromProvider($this->provider($provider),...$args);
    }
    protected function field(FieldKey $name):Field
    {
        return $this->fields[$name->value];
    }
    public function __toString(): string
    {
        try{
            return sprintf(
                static::FORMAT,
                ...array_values($this->fields)
            );
        }catch (Exception){
            Field::RefreshProviders(...array_values($this->providers));
            return sprintf(
                static::FORMAT,
                ...array_values($this->fields)
            );
        }
    }
    function getBinary(): string
    {
        return hex2bin(join('',$this->fields));
    }
    function refresh(): static
    {
        Field::RefreshProviders(...array_values($this->providers));
        return $this;
    }

    public static function Version1(?string $node=null):UUIDVersion1
    {
        return new UUIDVersion1($node);
    }

    public static function Version3(string $namespace, string $name)
    {
        return new UUIDVersion3($namespace,$name);
    }

    public static function Version4():UUIDVersion4
    {
        return new UUIDVersion4();
    }

}