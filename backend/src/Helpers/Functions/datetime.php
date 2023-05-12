<?php

if (!function_exists('format')) {
    function format(
        string $humanlyDateTime,
        string $format = 'Y-m-d H:i:s'
    ): string {
        return date($format, strtotime($humanlyDateTime));
    }
}
