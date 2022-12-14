<?php

declare(strict_types=1);

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
     * Mark action as test.
     *
     * @var bool If true function send not perfome anythink
     */
    protected static $mark_test = false;

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
    abstract protected function printSnapshot(&$print, $content, ...$var);

    /**
     * Dump if condition true.
     *
     * @param \Closure|bool                         $condition
     * @param string|array<int|string, mixed>|false $var
     *
     * @return void
     */
    public function dumpIf($condition, ...$var)
    {
        $condition = is_callable($condition)
            ? call_user_func($condition)
            : $condition;

        if ($condition === true) {
            $this->dump(...$var);
        }
    }

    /**
     * Mark class as testing,
     * so send method does't perfome anythink.
     *
     * @param bool $mark_as_test
     *
     * @return void
     */
    public static function markAsTest($mark_as_test = true)
    {
        self::$mark_test = $mark_as_test;
    }
}
