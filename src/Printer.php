<?php

declare(strict_types=1);

namespace Here;

use System\Console\Style\Style;
use System\Text\Str;

final class Printer
{
    /**
     * Content.
     *
     * @var array<string, array<int, mixed>|int|string>
     */
    private $content;

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
    private function send($out)
    {
        $out = $out === false ? '' : $out;
        (new Style($out))->out();
    }

    /**
     * dump information only.
     *
     * @return void
     */
    public function info()
    {
        ob_start();
        $this->printInfo($this->content);

        $this->send(ob_get_clean());
    }

    /**
     * Dump information and code snapshot.
     *
     * @param string|array<int|string, mixed>|false $var Addtion information to print
     *
     * @return void
     */
    public function dump($var = false)
    {
        ob_start();
        // print header info
        $this->printInfo($this->content);
        // print content
        $this->printSnapshot($this->content, $var);
        $this->send(ob_get_clean());
    }

    /**
     * dump all registered 'here'.
     *
     * @return void
     */
    public function dumpAll()
    {
        $heres = Here::getHere();
        array_pop($heres);

        foreach ($heres as $here) {
            (new static($here))->dump();
        }
    }

    /**
     * Dump information and count by group name.
     *
     * @param string $group
     *
     * @return void
     */
    public function count($group)
    {
        $count     = 0;
        $last_here = [];

        foreach (Here::getHere() as $here) {
            if ($here['group'] === $group) {
                $count++;
                $last_here = $here;
            }
        }

        if (empty($last_here)) {
            return;
        }

        ob_start();
        $this->printInfo($last_here, $count);
        $this->printSnapshot($last_here);
        $this->send(ob_get_clean());
    }

    /**
     * Count all avilable group.
     *
     * @return void
     */
    public function countAll()
    {
        $indexed = [];
        foreach (Here::getHere() as $here) {
            if ($here['group'] === '' | in_array($here['group'], $indexed)) {
                continue;
            }

            /** @var string */
            $group = $here['group'];
            $this->count($group);
            $indexed[] = $here['group'];
        }
    }

    // helper ----------------------------------------

    /**
     * print header information (file info line, code count).
     *
     * @param array<string, array<int, mixed>|int|string> $content
     * @param int|false                                   $with_counter
     *
     * @return void
     */
    private function printInfo($content, $with_counter = false)
    {
        $print = new Style("\n");

        $print->push(' work ')->textDarkGray()->bgGreen()->reverse();
        if ($with_counter !== false) {
            $print->push(' ' . $with_counter . 'x')->textDim();
        }
        $print->push(' - ')->textDim();
        // @phpstan-ignore-next-line
        $print->push('`' . $content['file'] . '`')->text_yellow_500();
        $print->push(':')->textDim();
        // @phpstan-ignore-next-line
        $print->push('' . $content['line'])->text_blue_500();
        $print->out();
    }

    /**
     * print code snapshot.
     *
     * @param array<string, array<int, mixed>|int|string> $content
     * @param string|array<int|string, mixed>|false       $var
     *
     * @return void
     */
    public function printSnapshot($content, $var = false)
    {
        $print = new Style("\n");

        $max_line = ((int) $content['line']) + 3;
        $lenght   = \strlen((string) $max_line);

        // @phpstan-ignore-next-line
        foreach ($content['capture'] as $line => $code) {
            $current = $line === $content['line'];
            $arrow   = $current ? '-> ' : '   ';

            $print($arrow)->textGreen();
            $print->push(Str::fill((string) $line, ' ', $lenght) . ' | ' . $code)->textDim();
            if ($var !== false && $current) {
                if (is_array($var)) {
                    $var = "\n" . json_encode($var, JSON_PRETTY_PRINT);
                }
                $print->push(Str::fill('', ' ', $lenght) . 'var : ')->textLightYellow()->push($var)->new_lines();
            }
            $print->out(false);
        }

        $print('')->out(false);
    }
}
