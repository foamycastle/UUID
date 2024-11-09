<?php

namespace Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider;

class CounterProvider extends Provider implements CounterProviderApi {

    /**
     * @var int The current value of the counter
     */
    protected int $count;
    
    /**
     * @var int The value by which the counter will increment
     */
    protected int $incValue;

    /**
     * @var int The counter's minimum value
     */
    protected int $minValue;

    /**
     * @var int The counter's maximum value
     */
    protected int $maxValue;

    /**
     * Create an instance of a counter provider
     */
    protected function __construct(
        int $minValue,
        int $maxValue,
        int $incValue,
    )
    {
        $this->key=ProviderKey::COUNTER;
        $this->incValue = $incValue;
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;

        /*
        Register this provider with the static facade
         */
        $this->register();
    }

    public function resetCount(): static
    {
        $this->count = $this->minValue ?? 0;
        return $this;
    }

    public function setMax(int $max): static
    {
        $this->maxValue = $max;
        return $this;
    }

    public function setMin(int $min): static
    {
        $this->minValue = $min;
        return $this;
    }

    public function setIncrement(int $increment): static
    {
        $this->incValue = $increment;
        return $this;
    }

    public function refreshData():static
    {
        if(!isset($this->incValue)) return $this;
        $this->count =+ $this->incValue;
        return $this;
    }

    public function getValue():int
    {
        return $this->count ?? 0;
    }

    public function getData():int
    {
        return $this->count ?? 0;
    }

    public function atMax(): bool
    {
        if(!isset($this->count) || !isset($this->maxValue)) return false;
        return ($this->count)==($this->maxValue);
    }

    public function __toString(): string
    {
        return '';
    }

    /**
     * Return a new instance of the current object
     * @param $args array{0:int,1:int,2:int}|array{'minValue':int,'maxValue':int,'incValue':int}
     * @return $this
     */
    public function __invoke(...$args): static
    {
        return new self(
            ...$args
        );
    }

}