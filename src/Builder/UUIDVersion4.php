<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Batchable;
use Foamycastle\UUID\Field;
use Foamycastle\UUID\Field\FieldKey;
use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\UUIDBuilder;

class UUIDVersion4 extends UUIDBuilder implements Batchable
{

    protected string $output;

    protected function __construct()
    {
        parent::__construct(4);

        $this->registerProvider(ProviderKey::RandomHex,32);

        $this->registerField(
            FieldKey::RAN_TIME_LO,
            ProviderKey::RandomHex
        )
            ->length(8);

        $this->registerField(
            FieldKey::RAN_TIME_MID,
            ProviderKey::RandomHex
        )
            ->length(4)
            ->startAt(8);

        $this->registerField(
            FieldKey::RAN_TIME_HI,
            ProviderKey::RandomHex
        )
            ->length(4)
            ->startAt(12)
            ->applyVersion(4);

        $this->registerField(
            FieldKey::RAN_VAR,
            ProviderKey::RandomHex
        )
            ->length(4)
            ->startAt(16)
            ->applyVariant();

        $this->registerField(
            FieldKey::RAN_NODE,
            ProviderKey::RandomHex
        )
            ->length(12)
            ->startAt(20);
    }
    public function __toString(): string
    {
        return $this->output ??= parent::__toString();
    }

    function batch(int $count): iterable
    {
        Provider::Refresh($this->provider(ProviderKey::RandomHex));
        while($count-- > 0){
            unset($this->output);
            yield $this;
            Provider::Refresh($this->provider(ProviderKey::RandomHex));
        }
    }

}