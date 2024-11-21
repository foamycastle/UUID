<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\ProviderApi;

class StaticNodeProvider extends NodeProvider
{
    private const string VERIFY_REGEX='/^[a-f0-9\:\-]{12,17}$/i';
    public function __construct(string $inputNode)
    {
        $this->random=new RandomWord(48);
        if(!$this->verifyNodeValue($inputNode)){
            $this->useRandom=true;
            self::$nodes[]=$this->random->toHex();
        }else{
            self::$nodes[]=strtolower($inputNode);
        }
    }

    private function verifyNodeValue(string $nodeValue):bool
    {
        return preg_match(self::VERIFY_REGEX,$nodeValue);
    }
    function refreshData(): static
    {
        return $this;
    }

    function reset(): static
    {
        return $this;
    }

    function toHex(): string
    {
        return self::$nodes[0];
    }

}