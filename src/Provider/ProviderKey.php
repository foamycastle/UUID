<?php

namespace Foamycastle\UUID\Provider;

enum ProviderKey
{
    case GREGOR_V1;
    case GREGOR_V6;
    case UNIX_TIME;
    case COUNTER;
    case RANDOM;
    case NODE_DARWIN;
    case NODE_WIN;
    case NODE_LINUX;
    case NODE_FREEBSD;
}
