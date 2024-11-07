<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider\TimeProvider;

class UnixTime extends TimeProvider
{
    public function __construct()
    {
        parent::__construct(ProviderKey::UNIX_TIME);
        $this->refreshData();
    }

    public function __invoke(...$args): static
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function getData(): int
    {
        return $this->time ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function refreshData(): static
    {
        ['sec'=>$sec,'usec'=>$usec]=gettimeofday();
        $this->time = $usec + ($sec*1000000);
        return $this;
    }

}