\<?php

namespace Foamycastle\UUID;

interface FieldApi
{
    function pad(int $length,string $char='0'):static;
}