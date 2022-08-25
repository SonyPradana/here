<?php

declare(strict_types=1);

namespace Here;

use Here\Abstracts\Printer;

if (!function_exists('here')) {
    /**
     * print debug information.
     *
     * @param string $group Group name use for count by group name
     *
     * @return Printer
     */
    function here($group = '')
    {
        /** @var array<int, array<string|int, string|int>> */
        $debug         = debug_backtrace();
        $file          = (string) $debug[0]['file'];
        $line          = (int) $debug[0]['line'];
        $line_count    = (int) Config::get('line');
        $print_var_end = (bool) Config::get('print_var_end', false);

        return (new Here($group))
            ->printVarEndOfCode($print_var_end)
            ->here($file, $line, $line_count);
    }
}

if (!function_exists('track')) {
    /**
     * print debug backtrace information.
     *
     * @return void
     */
    function track()
    {
        /** @var array<int, array<string, string|int>> */
        $debug = debug_backtrace();

        foreach ($debug as $track) {
            /** @var string */
            $file = $track['file'];
            /** @var int */
            $line = $track['line'];
            (new Here())->here($file, $line)->dump();
        }
    }
}

if (!function_exists('work')) {
    /**
     * print debug backtrace information.
     *
     * @param string|array<int|string, mixed>|false $var Addtion information to print
     *
     * @return void
     */
    function work(...$var)
    {
        /** @var array<int, array<string|int, string|int>> */
        $debug         = debug_backtrace();
        $file          = (string) $debug[0]['file'];
        $line          = (int) $debug[0]['line'];
        $line_count    = (int) Config::get('line');
        $print_var_end = (bool) Config::get('print_var_end', false);

        (new Here())
            ->printVarEndOfCode($print_var_end)
            ->here($file, $line, $line_count)
            ->dump(...$var);
    }
}
