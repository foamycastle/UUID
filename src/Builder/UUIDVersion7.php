<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Batchable;
use Foamycastle\UUID\Field\FieldKey;
use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\UUIDBuilder;

class UUIDVersion7 extends UUIDBuilder implements Batchable
{
    protected function __construct()
    {
        parent::__construct(7);

        $this->registerProvider(
            ProviderKey::UnixTime
        );
        $this->registerProvider(
            ProviderKey::RandomHex,
            20
        );

        $this->registerField(
            FieldKey::TIME_HI,
            ProviderKey::UnixTime
        )
            ->length(8)
            ->shiftRight(16)
            ->bitMask(0xFFFFFFFF);

        $this->registerField(
            FieldKey::TIME_MID,
            ProviderKey::UnixTime
        )
             ->length(4)
             ->bitMask(0xFFFF);

        $this->registerField(
            FieldKey::TIME_LOAV,
            ProviderKey::RandomHex
        )
             ->length(4)
             ->applyVersion(7);

        $this->registerField(
            FieldKey::TIME_VAR,
            ProviderKey::RandomHex
        )
             ->length(4)
             ->applyVariant()
             ->startAt(4);

        $this->registerField(
            FieldKey::TIME_NODE,
            ProviderKey::RandomHex
        )
             ->length(12)
             ->startAt(8);
    }

    public function batch(int $count): iterable
    {
        Provider::Refresh(...$this->providers);
        while ($count-- > 0) {
            yield $this;
            Provider::Refresh($this->provider(ProviderKey::RandomHex));
        }
    }

}