<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\Field\FieldKey;
use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\UUIDBuilder;

class UUIDVersion4 extends UUIDBuilder
{

    protected string $output;

    protected function __construct()
    {
        parent::__construct(4);
        $this->registerField(
            FieldKey::RAN_TIME_LO,
            ProviderKey::RandomWord,
            32
        );
        $this->registerField(
            FieldKey::RAN_TIME_MID,
            ProviderKey::RandomWord,
            16
        );
        $this->registerField(
            FieldKey::RAN_TIME_HI,
            ProviderKey::RandomWord,
            16
        )
            ->applyVersion(4);
        $this->registerField(
            FieldKey::RAN_VAR,
            ProviderKey::RandomWord,
            16
        )
            ->applyVariant();
        $this->registerField(
            FieldKey::RAN_NODE,
            ProviderKey::RandomWord,
            48
        );
    }
    public function __toString(): string
    {
        return $this->output ??= parent::__toString();
    }

}