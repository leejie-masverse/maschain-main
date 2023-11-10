<?php

use Illuminate\Support\Carbon;

if ( ! function_exists('carbon')) {

    /**
     * Carbon helper
     *
     * @param $value
     * @param $timezone
     *
     * @return \Carbon\Carbon
     */
    function carbon($value = null, $timezone = null)
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        return Carbon::parse($value, $timezone);
    }
}

if ( ! function_exists('user_carbon')) {
    /**
     * Local carbon helper
     *
     * @param $value
     *
     * @return \Carbon\Carbon
     */
    function user_carbon($value = null)
    {
        if ($value == null) {
            return '-';
        }

        return Carbon::parse($value)->setTimezone(config('app.user_timezone'));
    }
}

if ( ! function_exists('utc_carbon')) {
    /**
     * Convert to UTC carbon helper
     *
     * @param $value
     *
     * @return \Carbon\Carbon
     */
    function utc_carbon($value = null)
    {
        return Carbon::parse($value, config('app.user_timezone'))->setTimezone('UTC');
    }
}

if ( ! function_exists('combine_datetime_input')) {

    /**
     * Combine date input and time input
     *
     * @param $value
     * @param $timezone
     *
     * @return \Carbon\Carbon
     */
    function combine_datetime_input($date, $time)
    {
        $start_at = date('Y-m-d H:i:s', strtotime("$date $time"));

        return Carbon::parse($start_at, 'Asia/Kuala_Lumpur');
    }
}
