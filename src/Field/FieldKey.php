<?php

namespace Foamycastle\UUID\Field;

enum FieldKey:string
{
    case TIME_LO='time_lo';
    case TIME_LOAV='time_low_and_version';
    case TIME_MID='time_mid';
    case TIME_HI='time_hi';
    case TIME_HIAV='time_hi_and_version';
    case TIME_NODE='system_node';
    case TIME_VAR='sequential_counter_and_variant';
    case RAN_VAR='random_gen_and_variant';
    case RAN_NODE='random_gen_node_field';
    case RAN_TIME_LO='randon_gen_time_low';
    case RAN_TIME_MID='randon_gen_time_mid';
    case RAN_TIME_HI='randon_gen_time_hi';
    case HASH_VAR='hash_string_and_variant';
    case HASH_NODE='hash_string_node_field';
    case HASH_TIME_LO='hash_time_lo_field';
    case HASH_TIME_MID='hash_time_mid_field';
    case HASH_TIME_HI='hash_time_hi_field';
}
