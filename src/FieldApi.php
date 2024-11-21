<?php

namespace Foamycastle\UUID;

interface FieldApi
{
    function getValue(int $padLength=0,string $padChar='0'): mixed;
    function length(int $fieldLength):static;
    function applyVersion(int $version):static;
    function applyVariant(int $variant=2):static;
}