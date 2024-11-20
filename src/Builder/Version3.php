<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\Provider\HashProvider;
use Foamycastle\UUID\UUIDBuilder;

class Version3 extends UUIDBuilder
{
    protected HashProvider $hashProvider;
    protected string $hashString;
    protected function __construct(string $namespace,string $name)
    {
        parent::__construct(3);
        $this->hashProvider=new HashProvider($namespace,$name,3);
        $this->build();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return self::groupString($this->hashString);
    }

    public function build(...$args): UUIDBuilder
    {
        $this->hashString=$this->hashProvider->getData();
        return $this;
    }

    protected function refreshData(...$fields): UUIDBuilder
    {
        // TODO: Implement refreshData() method.
    }

}