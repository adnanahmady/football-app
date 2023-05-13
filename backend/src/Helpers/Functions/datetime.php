<?php

if (!function_exists('format')) {
    function format(
        string $humanlyDateTime,
        string $format = 'Y-m-d H:i:s'
    ): string {
        return date($format, strtotime($humanlyDateTime));
    }
}

if (!function_exists('now')) {
    function now(string $format = 'Y-m-d H:i:s'): string
    {
        return format('now', $format);
    }
}
