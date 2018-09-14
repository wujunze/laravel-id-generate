<?php

namespace WuJunze\IdGen;


if (!function_exists('get_current_ms')) {
    /**
     * @return int
     */
    function get_current_ms()
    {
        list($t1, $t2) = explode(' ', microtime());

        return (int)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}


if (!function_exists('get_format_time')) {

    /**
     * @return false|string
     */
    function get_format_time()
    {
        return date('Y-m-d H:i:s');
    }
}


if (!function_exists('get_age_by_birthday')) {
    /**
     * @param $birthday
     * @return string
     */
    function get_age_by_birthday($birthday)
    {
        if (empty($birthday)) {
            return '';
        }

        return (string)(date('Y') - substr($birthday, 0, 4));
    }
}
