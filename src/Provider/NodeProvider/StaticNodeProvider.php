<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\ProviderApi;

/**
 * Provides a static node value to a field
 * @author Aaron Sollman <unclepong@gmail.com>
 */
class StaticNodeProvider extends NodeProvider
{
    /**
     * for validating user-suppied node values
     */
    private const string VERIFY_REGEX='/^[a-f0-9\:\-]{12,17}$/i';

    public function __construct(string $inputNode)
    {
        //prepare a random value in case an actual node cannot be read
        $this->random=new RandomWord(48);

        //validate the input string
        if(!$this->verifyNodeValue($inputNode)){

            //not valid. use a random node value instead
            $this->useRandom=true;
            self::$nodes[]=$this->random->toHex();
        }else{
            self::$nodes[]=strtolower($inputNode);
        }
    }

    /**
     * Perform a regex match on a user supplied node value
     * @param string $nodeValue
     * @return bool TRUE if the supplied user-value matches
     */
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