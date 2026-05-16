<?php

declare(strict_types=1);

/**
 * mb_split() existe en PHP 8.4+ dentro de mbstring. Laravel 13 lo usa en Str::studly().
 * En PHP 8.3, o si Windows bloquea php_mbstring.dll, hay que definir esta función.
 *
 * @see https://www.php.net/manual/en/function.mb-split.php
 */
if (! function_exists('mb_split')) {
    function mb_split(string $pattern, string $string, int $limit = -1): array|false
    {
        if ($pattern === '') {
            trigger_error('mb_split(): Argument #1 ($pattern) must not be empty', E_USER_WARNING);

            return false;
        }

        if ($limit === 0) {
            $limit = 1;
        }

        $delimiter = '/'.str_replace('/', '\\/', $pattern).'/u';
        $pregLimit = $limit < 0 ? -1 : $limit;

        $parts = preg_split($delimiter, $string, $pregLimit);

        return $parts === false ? false : $parts;
    }
}
