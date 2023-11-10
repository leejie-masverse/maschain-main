<?php

use Cake\Utility\Hash;

if ( ! function_exists('hash_is_sequential')) {
    /**
     * Check if an array is sequential
     *
     * @param array $arr
     *
     * @return bool
     */
    function hash_is_sequential(array $arr)
    {
        return Hash::numeric(array_keys($arr));
    }
}

if ( ! function_exists('hash_is_associative')) {
    /**
     * Check if an array is associative
     *
     * @param array $arr
     *
     * @return bool
     */
    function hash_is_associative(array $arr)
    {
        return ! hash_is_sequential($arr);
    }
}

if ( ! function_exists('hash_flatten')) {
    /**
     * Flatten nested array into dot array
     *
     * @param array $arr
     *
     * @return array
     */
    function hash_flatten(array $arr)
    {
        return Hash::flatten($arr);
    }
}

if ( ! function_exists('hash_expand')) {
    /**
     * Expand dot array into nested array
     *
     * @param array $arr
     *
     * @return array
     */
    function hash_expand(array $arr)
    {
        return Hash::expand($arr);
    }
}

if ( ! function_exists('hash_filter_recursive')) {
    /**
     * Recursively filters array.
     *
     * @param array $arr
     *
     * @return array
     */
    function hash_filter_recursive(array $arr)
    {
        return Hash::filter($arr);
    }
}
