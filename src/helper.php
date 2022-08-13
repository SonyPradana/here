<?php

declare(strict_types=1);

namespace Here;

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
        $debug = debug_backtrace();
        $file  = (string) $debug[0]['file'];
        $line  = (int) $debug[0]['line'];

        return (new Here($group))->here($file, $line);
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
