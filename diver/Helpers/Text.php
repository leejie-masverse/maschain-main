<?php

use Cake\Utility\Text;

if (!function_exists('text_insert')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function text_insert($str, $data, array $options = [])
    {
        return Text::insert($str, $data, $options);
    }
}
