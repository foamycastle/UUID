<?php

namespace Foamycastle\UUID;

use Foamycastle\UUID\Field\FieldInt;
use Foamycastle\UUID\Field\FieldIntApi;
use Foamycastle\UUID\Field\FieldString;
use Foamycastle\UUID\Field\FieldStringApi;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\Provider\ProvidesInt;

abstract class Field implements FieldApi,\Stringable
{
    public const string TIME_LO='time_lo';
    public const string TIME_LOAV='time_low_and_version';
    public const string TIME_MID='time_mid';
    public const string TIME_HI='time_hi';
    public const string TIME_HIAV='time_hi_and_version';
    public const string TIME_NODE='system_node';
    public const string TIME_VAR='sequential_counter_and_variant';
    public const string RAN_VAR='random_gen_and_variant';
    public const string RAN_NODE='random_gen_node_field';
    public const string RAN_TIME_LO='randon_gen_time_low';
    public const string RAN_TIME_MID='randon_gen_time_mid';
    public const string RAN_TIME_HI='randon_gen_time_hi';
    public const string HASH_VAR='hash_string_and_variant';
    public const string HASH_NODE='hash_string_node_field';
    public const string HASH_TIME_LO='hash_time_lo_field';
    public const string HASH_TIME_MID='hash_time_mid_field';
    public const string HASH_TIME_HI='hash_time_hi_field';

        /**
     * @var Provider|ProvidesHex|ProvidesInt provides a dynamic value instead of a static value
     */
    protected Provider|ProvidesHex|ProvidesInt $provider;

    /**
     * @var callable[] A queue of operations to perform before output
     */
    protected array $processQueue;
    protected int $fieldLength;

    protected function __construct()
    {
        $this->processQueue=[];
    }

    public function length(int $fieldLength): static
    {
        $this->fieldLength=$fieldLength;
        return $this;
    }

    protected function appendProcessQueue(callable $process):void
    {
        $this->processQueue[] = $process;
    }
    protected function prependProcessQueue(callable $process):void
    {
        array_unshift($this->processQueue,$process);
    }
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

    public function resetProvider():void
    {
        $this->provider->reset();
    }

    /**
     * @param class-string|ProviderApi $provider
     * @param ...$args
     * @return (FieldIntApi&FieldApi)|(FieldApi&FieldStringApi)|null
     */
    public static function FromProvider(string|ProviderApi $provider, ...$args): null|(FieldApi&FieldStringApi)|(FieldApi&FieldIntApi)
    {
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