<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Field\FieldInt;
use Foamycastle\UUID\Field\FieldIntApi;
use Foamycastle\UUID\Field\FieldString;
use Foamycastle\UUID\Field\FieldStringApi;
use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\Provider\ProvidesInt;
use Stringable;

/**
 * Parent class for field objects.  Field objects use a unit-of-work pattern
 * to perform the operations necessary to process data ingested from Provider objects
 */
abstract class Field implements FieldApi, Stringable
{
    /**
     * @var Provider|ProvidesHex|ProvidesInt provides data to field
     */
    protected Provider|ProvidesHex|ProvidesInt $provider;

    /**
     * @var callable[] A queue of operations to process data
     */
    protected array $processQueue;

    /**
     * @var int the length of the field in hexadecimal characters
     */
    protected int $fieldLength;

    protected function __construct()
    {
        $this->processQueue=[];
    }

    /**
     * Set the field length
     * @param int $fieldLength the length of the field in hexadecimal characters
     * @return static
     */
    public function length(int $fieldLength): static
    {
        $this->fieldLength=$fieldLength;
        return $this;
    }

    /**
     * Add a unit of work the process queue
     * @param callable $process The operation to be perform on the data
     */
    protected function appendProcessQueue(callable $process):void
    {
        $this->processQueue[] = $process;
    }

    /**
     * Prepend a unit of work to the queue
     * @param callable $process The operation to be perform on the data
     */
    protected function prependProcessQueue(callable $process):void
    {
        array_unshift($this->processQueue,$process);
    }

    /**
     * Process the units of work in the order they were added
     * @return mixed the processed data
     */
    protected function processQueue():mixed
    {
        $value=$this->getValue();
        foreach ($this->processQueue as $process) {
            $value=$process($value);
        }
        return $value;
    }

    /**
     * Set the provider
     * @param Provider $provider
     * @return void
     */
    protected function setProvider(ProviderApi $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * Triggers the provider to update or provide new data
     * @return void
     */
    public function refreshProvider():void
    {
        $this->provider->refreshData();
    }

    /**
     * Trigger a state reset of the data provider
     * @return void
     */
    public function resetProvider():void
    {
        $this->provider->reset();
    }

    /**
     * Create a new field object using an estblished provider object
     * @param class-string|ProviderApi $provider The provider object or the class string to instantiatiate the provider
     * @param ...$args Any arguments that are needed to instantiatiate the provider object
     * @return (FieldIntApi&FieldApi)|(FieldApi&FieldStringApi)|null
     */
    public static function FromProvider(string|ProviderApi $provider, ...$args): null|(FieldApi&FieldStringApi)|(FieldApi&FieldIntApi)
    {
        //class strings are accepted as are Provider objects.
        //If a class string is provided, attempt to instantiate the Provider
        if(is_string($provider)){
            if(class_exists($provider)) {
                /** @var ProviderApi $provider */
                $provider = new $provider(...$args);
                $provider->refreshData();
            }else{
                return null;
            }
        }

        if($provider instanceof ProviderApi && $provider instanceof ProvidesInt)
            return new FieldInt($provider);

        if($provider instanceof ProviderApi && $provider instanceof ProvidesHex)
            return new FieldString($provider);

        return null;
    }

    /**
     * Trigger a refresh of all provider objects specified
     * @param ...$providers FieldApi
     * @return void
     */
    public static function RefreshProviders(...$providers):void
    {
        foreach ($providers as $provider) {
            $provider->refreshProvider();
        }
    }

}