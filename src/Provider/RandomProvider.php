<?php

namespace Foamycastle\UUID\Provider;

defined('MSB')||define('MSB', 1);
defined('LSB')||define('LSB', 0);
defined('MAX')||define('MAX', (2**48)-1);

use Error;
use Foamycastle\UUID\Provider;

class RandomProvider extends Provider
{

    protected int $value;
    protected int $randomMin;
    protected int $randomMax;
    public function __construct(
        protected int $bitLength = 48
    )
    {
        $this->bitLength=$this->normalizeBitLength($this->bitLength);

        $this->randomMin=intval((2**($this->bitLength-1)));
        $this->randomMax=intval((2**($this->bitLength)-1));
    }

    /**
     * Returns the number of bits needed to represent a given decimal
     * @param int $value the integer under test
     * @return int the number of bits needed to represent `$value`
     */
    protected function getBitCount(int $value): int
    {
        return (int)floor(log($value, 2)) + 1;
    }

    /**
     * Return a new sausage
     * @param $args array{0:int}|array{'len':int}
     * @return $this
     */
    public function __invoke(...$args): static
    {
        try{
            @['len'=>$len]=$args;
        }catch (\Exception $exception){
            @[$len]=$args;
        }
        return new self($len);
    }

    /**
     * @inheritDoc
     */
    public function getData(): int
    {
        return $this->value ?? $this->refreshData()->getData();
    }

    /**
     * @inheritDoc
     */
    public function refreshData(): static
    {
        try{
            $this->value = random_int($this->randomMin, $this->randomMax);
        }catch (\Exception | \Error $e){
            try{
                $extraBits=$this->bitLength % 8;
                $this->value=random_bytes(intval($this->bitLength/8));
                $this->value .= $extraBits > 0
                    ? chr(rand(1<<(8-$extraBits),(2**($extraBits)-1)<<(8-$extraBits)))
                    : '';
            }catch (\Exception | \Error $e){
                $this->value=rand($this->randomMin, $this->randomMax);
            }
        } finally {
            return $this;
        }
    }

    /**
     * Ensure an integer representing the number of bits in a word is a value between 1 and 64
     * @param int $bitLength The integer to test
     * @return int<1,60>
     */
    private function normalizeBitLength(int $bitLength): int
    {
        if($bitLength > 60){
            $bitLength = 60;
        }
        if($bitLength <= 0){
            $bitLength = 1;
        }
        return $bitLength;
    }

}