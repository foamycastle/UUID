<?php

namespace Foamycastle\UUID\Provider;

enum ProviderKey
{
    case GREGOR_V1;
    case GREGOR_V6;
    case UNIX_TIME;
    case COUNTER;
}
