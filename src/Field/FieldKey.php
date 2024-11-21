<?php

namespace Foamycastle\UUID\Field;

/**
 * A enum of keys used to identify the fields registered in a UUID builder
 * The purpose of doing it this way is to ensure uniqueness and consistency
 */
enum FieldKey:string
{
    /**
     * Version 1
     */
    case TIME_LO='time_lo';

    /**
     * Version 6
     */
    case TIME_LOAV='time_low_and_version';

    /**
     * Version 1,2,6,7
     */
    case TIME_MID='time_mid';

    /**
     * Verion 6
     */
    case TIME_HI='time_hi';

    /**
     * Version 1,2
     */
    case TIME_HIAV='time_hi_and_version';

    /**
     * Version 1,2,6
     */
    case TIME_NODE='system_node';

    /**
     * Version 1
     */
    case TIME_VAR='sequential_counter_and_variant';

    /**
     * Version 1,4,7
     */
    case RAN_VAR='random_gen_and_variant';

    /**
     * Version 1,2,4,6,7
     */
    case RAN_NODE='random_gen_node_field';

    /**
     * Version 4
     */
    case RAN_TIME_LO='randon_gen_time_low';

    /**
     * Version 4
     */
    case RAN_TIME_MID='randon_gen_time_mid';

    /**
     * Version 4
     */
    case RAN_TIME_HI='randon_gen_time_hi';

    /**
     * Version 3,5
     */
    case HASH_VAR='hash_string_and_variant';

    /**
     * Version 3,5
     */
    case HASH_NODE='hash_string_node_field';

    /**
     * Version 3,5
     */
    case HASH_TIME_LO='hash_time_lo_field';

    /**
     * Version 3,5
     */
    case HASH_TIME_MID='hash_time_mid_field';

    /**
     * Version 3,5
     */
    case HASH_TIME_HI='hash_time_hi_field';
}
