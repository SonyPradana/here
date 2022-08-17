<?php

namespace Here\Abstracts;

use Here\Contracts\DumbInterface;
use System\Console\Style\Style;

abstract class Printer implements DumbInterface
{
    /**
     * Content.
     *
     * @var array<string, array<int, mixed>|int|string>
     */
    protected $content;

    /**
     * Style printer.
     *
     * @var Style
     */
    protected $print;

    /**
     * Create new instance with content.
     *
     * @param array<string, array<int, mixed>|int|string> $content
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Send output.
     *
     * @param string|false $out
     *
     * @return void
     */
    abstract protected function send($out);

    /**
     * print header information (file info line, code count).
     *
     * @param Style                                       $print
     * @param array<string, array<int, mixed>|int|string> $content
     * @param int|false                                   $with_counter
     *
     * @return void
     */
    abstract protected function printInfo(&$print, $content, $with_counter = false);

    /**
     * print code snapshot.
     *
     * @param Style                                       $print
     * @param array<string, array<int, mixed>|int|string> $content
     * @param string|array<int|string, mixed>|false       $var
     *
     * @return void
     */
    abstract protected function printSnapshot(&$print, $content, $var = false);
}
