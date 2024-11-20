<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\Provider\RandomProvider;
use Foamycastle\UUID\UUIDBuilder;

class Version4 extends UUIDBuilder
{
    protected function __construct()
    {
        parent::__construct(4);

        $this->addField(
            UUIDBuilder::GROUP1,
            Field::FromProvider(RandomProvider::class,32)
        );
        $this->addField(
            UUIDBuilder::GROUP2,
            Field::FromProvider(RandomProvider::class,16),
        );
        $this->addField(
            self::GROUP3,
            Field::FromProvider(RandomProvider::class,16)->orValue(4<<12)
        );
        $this->addField(
            self::GROUP4,
            Field::FromProvider(RandomProvider::class,16)->orValue(2<<14)
        );
        $this->addField(
            self::GROUP5,
            Field::FromProvider(RandomProvider::class,48)
        );
        $this->refreshData();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return sprintf(
            UUIDBuilder::FORMAT,
            ...$this->allFieldsToHex()
        );
    }

    public function build(...$args): UUIDBuilder
    {
        return $this;
    }

    protected function refreshData(...$fields): UUIDBuilder
    {
        Field::RefreshValues($fields);
        return $this;
    }

}