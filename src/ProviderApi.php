<?php

namespace Foamycastle\UUID;

interface ProviderApi
{
    function refreshData():self;
    function getData():mixed;
}