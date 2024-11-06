<?php

namespace Foamycastle\UUID;

abstract class Field implements FieldApi
{

    /**
     * Constants prefixed with 'XFMR' are used in the ::getTransformer method as match statement keys to return callables
     * as transformers. In order to define constants in this class or child classes, prefix it with 'XFMR' and let the value of
     * that constant be a string
     */
    public const XFMR='';

    /**
     * @var mixed $value the value of the field.  For fields that are calculated, the value will be an int.
     * For fields that are generated or retrieved from the system, the value will be converted to an int from a string.
     */
    protected mixed $value;

    /**
     * @var int  $charLength the length of the field in ASCII characters. A UUID can be composed of one field or many.  One field would need to be the entire length of the UUID, 32, while a component field would need to be less.  The minimum field length is 1.
     */
    protected int $charLength;

    /**
     * Each field contains a discrete value. The variant component of a UUID contains 2 bits and the 'counter' of a version 1 UUID contains 14 bits.  These fields are then combined to compose a 16-bit group of characters in a UUID string.  Instead of keeping these objects separate and then performing a bunch of extra operations to combine them, this property can 'link' the two fields, the variant field having a bit length of 16 and an offset of 14 means that the actual variant component can only be 2 bits long.  Couple that with a counter field that also has a bit-length of 16 but only uses 14 bits of the 16 bit word.  These two fields being linked can be combined into 1 16 bit word at the time of UUID string construction. <br><br>If this property contains a reference to another field, the value from the referenced field is read and combined with the value of this field.
     * @var Field|null $linkedField
     */
    protected ?Field $linkedField;

    /**
     * @var Provider|null A provider class that provides the field with data.
     */
    protected ?Provider $provider;

    /**
     * @var string An identifier that is used to read specific data from the provider. If the `$provider` property is set, a provider key must be specified.
     */
    protected string $providerKey;

    public function setLink(Field $field): static
    {
        $this->linkedField = $field;
        return $this;
    }
    public function setProvider(Provider $provider): static
    {
        $this->provider = $provider;
        return $this;
    }
    public function setProviderKey(string $key): static
    {
        $this->providerKey = $key;
        return $this;
    }
    public function readProvider(): static
    {
        if(isset($this->providerKey) && isset($this->provider)) {
            $this->value = $this->provider->getData($this->providerKey);
        }
        return $this;
    }

    /**
     * Returns a callable that transforms a foreign field's data into this field's type and value.
     * Place anonymous functions within the match statement body. It is helpful to define class constants
     * and use those as match keys for each of the callables. Inside the callable body, use the 'use' statement in the function
     * declaration to gain access to the link field property to read the referenced field's value <br><br>
     * <b>NOTE:</b> Do not call this base function. The function below should be overridden in child classes so that it may return
     * transformers relevant to the child class.
     *
     * @param string<static::XFMR_*> $transformerKey
     * @return callable The callable that is returned should return only the field's value after it has been transformed to a new datatype
     */
    protected function getTransformer(string $transformerKey): callable{
        return match($transformerKey) {
            default=>function(){
                return null;
            }
        };
    }

}