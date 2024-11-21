<?php

namespace Foamycastle\UUID\Builder;

use Foamycastle\UUID\Field\FieldKey;
use Foamycastle\UUID\Provider\ProviderKey;
use Foamycastle\UUID\UUIDBuilder;
use Foamycastle\UUID\Batchable;

/**
 * UUID Type 3: MD5 Hash-based.
 * @link https://datatracker.ietf.org/doc/html/rfc9562#name-uuid-version-1
 * @author Aaron Sollman<unclepong@gmail.com>
 *
 */
class UUIDVersion3 extends UUIDBuilder
{
    protected string $output;
    protected function __construct(
        private readonly string $namespace,
        private readonly string $name
    )
    {
        parent::__construct(3);

        //Provides the hash string that comprises the UUID
        $this->registerProvider(
            ProviderKey::Hash,$this->namespace,$this->name,3
        );

        /* First group of 8 hexadecimal characters
         * Since this UUID version is essentially one long string,
         * each group of hexadecimal characters will be arbitrarily represented
         * by its own field
         */
        $this->registerField(
            FieldKey::HASH_TIME_LO,
            ProviderKey::Hash
        )
            ->length(8);

        $this->registerField(
            FieldKey::HASH_TIME_MID,
            ProviderKey::Hash
        )
            ->length(4)
            ->startAt(8);

        $this->registerField(
            FieldKey::HASH_TIME_HI,
            ProviderKey::Hash
        )
            ->length(4)
            ->startAt(12)
            ->applyVersion(3);

        $this->registerField(
            FieldKey::HASH_VAR,
            ProviderKey::Hash
        )
            ->length(4)
            ->startAt(16)
            ->applyVariant();

        $this->registerField(
            FieldKey::HASH_NODE,
            ProviderKey::Hash
        )
            ->length(12)
            ->startAt(20);
    }

    public function __toString(): string
    {
        //the UUID product is static. once the product is assembled,
        //cache it for further calls
        return $this->output ??= parent::__toString();
    }

}