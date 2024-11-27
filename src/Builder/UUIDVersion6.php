<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Batchable;
use Foamycastle\UUID\Field;
use Foamycastle\UUID\Field\FieldKey;
use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\NodeProvider\StaticNodeProvider;
use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\UUIDBuilder;

class UUIDVersion6 extends UUIDBuilder implements Batchable
{
    protected function __construct(null|string $staticNode=null)
    {
        parent::__construct(6);

        /*
         * If a primitive string was supplied as a static node,
         * wrap it in a StaticNodeProvider class. Otherwise, use a
         * SystemNodeProvider
         */

        $nodeProvider = is_string($staticNode)
            ? [ProviderKey::StaticNode,$staticNode]
            : [ProviderKey::SystemNode];


        //Provides the integer value of time
        $this->registerProvider(ProviderKey::GregorianTime);

        //Provides the clock sequence
        $this->registerProvider(ProviderKey::Counter,0,0x3fff);

        //Provide the node portion of the UUID
        $this->registerProvider(...$nodeProvider);

        //Time low field
        $this->registerField(
            FieldKey::TIME_HI,
            ProviderKey::GregorianTime
        )
             ->length(8)
             ->shiftRight(28)
             ->bitMask(0xFFFFFFFF);

        //Time mid field
        $this->registerField(
            FieldKey::TIME_MID,
            ProviderKey::GregorianTime
        )
             ->length(4)
             ->shiftRight(12)
             ->bitMask(0xFFFF);

        //Time high and version field
        $this->registerField(
            FieldKey::TIME_LOAV,
            ProviderKey::GregorianTime
        )
             ->length(4)
             ->bitMask(0x0FFF)
             ->applyVersion(6);

        //Counter and Variant field
        $this->registerField(
            FieldKey::TIME_VAR,
            ProviderKey::Counter
        )
             ->length(4)
             ->applyVariant();

        //Node field
        $this->registerField(
            FieldKey::TIME_NODE,
            $nodeProvider[0]
        )
             ->length(12);
    }

    function batch(int $count):iterable
    {
        Provider::Refresh($this->provider(ProviderKey::GregorianTime));
        Provider::ResetProvider($this->provider(ProviderKey::Counter));
        while ($count-- > 0) {
            yield $this;
            Field::RefreshProviders(
                $this->field(FieldKey::TIME_VAR),
                $this->field(FieldKey::TIME_NODE)
            );
        }
    }

}