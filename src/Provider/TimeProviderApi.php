<?php

namespace Foamycastle\UUID\Provider;

interface TimeProviderApi
{
    function getTimeValue():int;
}