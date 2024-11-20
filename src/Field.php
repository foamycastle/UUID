<?php

namespace Foamycastle\UUID;

abstract class Field implements FieldApi
{

    /**
     * @var mixed originally assigned field value before processing or value from provider
     */
    protected mixed $value;

    /**
     * @var Provider provides a dynamic value instead of a static value
     */
    protected Provider $provider;

    /**
     * @var callable[] A queue of operations to perform before output
     */
    protected array $processQueue;
    /**
     * Length in hexadecimal characters
     * @var int $fieldLength
     */
    protected int $fieldLength=0;

    protected function __construct()
    {
        $this->processQueue=[];
    }

    abstract protected function getValue():mixed;

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
    protected function setProvider(Provider $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * Indicates whether the provider's data should be used as a value in lieu of the scalar value property
     * @return bool
     */
    protected function useProvider():bool
    {
        return (isset($this->provider) && !isset($this->value));
    }

    /**
     * Triggers the provider to update or provide new data
     * @return void
     */
    protected function refreshProvider():void
    {
        $this->provider->refreshData();
    }

    protected function resetProvider():void
    {
        $this->provider->reset();
    }




}