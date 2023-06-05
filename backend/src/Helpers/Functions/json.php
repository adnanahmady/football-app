<?php

if (!function_exists('jsonToArray')) {
    function jsonToArray(
        string $jsonString,
        bool $autoCorrect = false
    ): array|false {
        $converter = new \App\Helpers\Json\JsonToArrayConverter($jsonString);

        if ($autoCorrect) {
            $converter->autoCorrect();
        }

        return $converter->convert();
    }
}
