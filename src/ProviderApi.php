<?php

namespace Foamycastle\UUID;

interface ProviderApi
{
    function refreshData():static;
    function reset():static;

}