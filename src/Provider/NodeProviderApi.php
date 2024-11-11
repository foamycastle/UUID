<?php

namespace Foamycastle\UUID\Provider;

interface NodeProviderApi
{
    function getAllNodes():array;
    function getAnyNode():string;
    function getFirstNode():string;
    function getLastNode():string;
}