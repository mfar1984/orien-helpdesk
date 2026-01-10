<?php

use App\Models\Setting;
use Carbon\Carbon;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        static $settings = null;
        
        if ($settings === null) {
            // Value is already decoded by model cast
            $settings = Setting::all()->pluck('value', 'key');
        }
        
        return $settings->get($key, $default);
    }
}

if (!function_exists('company_name')) {
    /**
     * Get company name from settings.
     * 
     * @return string
     */
    function company_name(): string
    {
        return setting('company_name', 'ORIEN Helpdesk');
    }
}

if (!function_exists('system_name')) {
    /**
     * Get system name from settings.
     * 
     * @return string
     */
    function system_name(): string
    {
        return setting('system_name', 'ORIEN Helpdesk');
    }
}

if (!function_exists('format_date')) {
    /**
     * Format a date using the system date format.
     * 
     * @param mixed $date
     * @return string
     */
    function format_date($date): string
    {
        if (empty($date)) {
            return '-';
        }
        
        $format = setting('date_format', 'd/m/Y');
        $timezone = setting('timezone', 'Asia/Kuala_Lumpur');
        
        if ($date instanceof Carbon) {
            return $date->timezone($timezone)->format($format);
        }
        
        return Carbon::parse($date)->timezone($timezone)->format($format);
    }
}

if (!function_exists('format_time')) {
    /**
     * Format a time using the system time format.
     * 
     * @param mixed $time
     * @return string
     */
    function format_time($time): string
    {
        if (empty($time)) {
            return '-';
        }
        
        $format = setting('time_format', 'H:i');
        $timezone = setting('timezone', 'Asia/Kuala_Lumpur');
        
        if ($time instanceof Carbon) {
            return $time->timezone($timezone)->format($format);
        }
        
        return Carbon::parse($time)->timezone($timezone)->format($format);
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format a datetime using the system date and time format.
     * 
     * @param mixed $datetime
     * @return string
     */
    function format_datetime($datetime): string
    {
        if (empty($datetime)) {
            return '-';
        }
        
        $dateFormat = setting('date_format', 'd/m/Y');
        $timeFormat = setting('time_format', 'H:i');
        $timezone = setting('timezone', 'Asia/Kuala_Lumpur');
        
        if ($datetime instanceof Carbon) {
            return $datetime->timezone($timezone)->format($dateFormat . ' ' . $timeFormat);
        }
        
        return Carbon::parse($datetime)->timezone($timezone)->format($dateFormat . ' ' . $timeFormat);
    }
}

if (!function_exists('app_timezone')) {
    /**
     * Get the application timezone.
     * 
     * @return string
     */
    function app_timezone(): string
    {
        return setting('timezone', 'Asia/Kuala_Lumpur');
    }
}
