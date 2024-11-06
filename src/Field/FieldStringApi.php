<?php

namespace Foamycastle\UUID\Field;

interface FieldStringApi extends \Stringable
{

    /**
     * Sets the internal flag to pad the output string with the pad char
     * @param bool $pad TRUE if it is desired that the output string is padded
     * @return $this
     */
    function pad(bool $pad): static;

}