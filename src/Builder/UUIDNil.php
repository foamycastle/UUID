<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Field\FieldKey;
use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\UUIDBuilder;

class UUIDNil extends UUIDBuilder
{
    protected function __construct()
    {
        parent::__construct(0);

        $this->registerProvider(
            ProviderKey::NilMaxProvider,
            true
        );

        $this->registerField(
            FieldKey::NILMAX_TIME_LO,
            ProviderKey::NilMaxProvider
        )
            ->length(8);

        $this->registerField(
            FieldKey::NILMAX_TIME_MID,
            ProviderKey::NilMaxProvider
        )
             ->startAt(8)
             ->length(4);

        $this->registerField(
            FieldKey::NILMAX_TIME_HI,
            ProviderKey::NilMaxProvider
        )
             ->startAt(12)
             ->length(4);

        $this->registerField(
            FieldKey::NILMAX_VAR,
            ProviderKey::NilMaxProvider
        )
             ->startAt(16)
             ->length(4);

        $this->registerField(
            FieldKey::NILMAX_TIME_NODE,
            ProviderKey::NilMaxProvider
        )
             ->startAt(20)
             ->length(12);
    }

}