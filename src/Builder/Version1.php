<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Field;
use Foamycastle\UUID\Provider\CounterProvider;
use Foamycastle\UUID\Provider\NodeProvider;
use Foamycastle\UUID\Provider\TimeProvider;
use Foamycastle\UUID\Provider\TimeProvider\GregorianTime;
use Foamycastle\UUID\UUIDBuilder;

class Version1 extends UUIDBuilder
{
    private TimeProvider $timeProvider;
    private CounterProvider $counterProvider;
    private NodeProvider $nodeProvider;
    protected function __construct()
    {
        parent::__construct(1);
        $this->timeProvider=new GregorianTime();
        $this->counterProvider=new CounterProvider(0,0x3ff,1);
        $this->nodeProvider=new NodeProvider();

        $this->addField(
            UUIDBuilder::TIME_LOW,
            Field::FromProvider($this->timeProvider)->pad(8)
        );
        $this->addField(
            UUIDBuilder::TIME_MID,
            Field::FromProvider($this->timeProvider)
        );
        $this->addField(
            UUIDBuilder::TIME_HAV,
            Field::FromProvider($this->timeProvider)
        );
        $this->addField(
            UUIDBuilder::COUNTER,
            Field::FromProvider($this->counterProvider)
        );
        $this->addField(
            UUIDBuilder::SYS_NODE,
            Field::FromProvider($this->nodeProvider)
        );
    }
    protected function refreshData(...$fields): UUIDBuilder
    {
        if(empty($fields)){
            Field::RefreshValues($this->getFields());
            return $this;
        }
        Field::RefreshValues($fields);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $this->build();
        return self::groupString(
            join("",...array_map(fn($x)=>$x->toHex(),$this->getFields()))
        );
    }
    public function build(...$args): UUIDBuilder
    {

        if(!empty($args)){
            if ($args[0] == self::INCREMENT_CLOCK) {
                $this->counterProvider->refreshData();
                return $this;
            }
            if ($args[0] == self::REFRESH_FIELDS){
                $this->timeProvider->refreshData();
                $this->counterProvider->reset();
            }
        }else{
            $this->refreshData();
        }
        $this(UUIDBuilder::TIME_LOW)            ->andValue(0xffffffff);
        $this(UUIDBuilder::TIME_MID)            ->shiftRight(32)->andValue(0xFFFF);
        $this(UUIDBuilder::TIME_HAV)            ->shiftRight(48)->orValue(1<<12);
        $this(UUIDBuilder::COUNTER)             ->orValue(2<<14);
        return $this;
    }

}