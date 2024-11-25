<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider\NodeProvider\StaticNodeProvider;
use Foamycastle\UUID\Provider\NodeProvider\SysNodeProvider;
use Foamycastle\UUID\Provider\RandomProvider\RandomHex;
use Foamycastle\UUID\Provider\RandomProvider\RandomInt;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\Provider\TimeProvider\GregorianTime;
use Foamycastle\UUID\Provider\TimeProvider\UnixTime;

/**
 * Backed enum that provides the class strings for all providers. This
 * ensures reliability and uniqueness when registering providers in a UUID builder.
 * @author Aaron Sollman <unclepong@gmail.com>
 */
enum ProviderKey:string
{
    case GregorianTime=GregorianTime::class;
    case UnixTime=UnixTime::class;
    case SystemNode=SysNodeProvider::class;
    case StaticNode=StaticNodeProvider::class;
    case RandomHex=RandomHex::class;
    case RandomInt=RandomInt::class;
    case RandomWord=RandomWord::class;
    case Counter=CounterProvider::class;
    case Hash=HashProvider::class;
    case NilMaxProvider=NilMax::class;

}
