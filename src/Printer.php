<?php

declare(strict_types=1);

namespace Here;

use Here\Abstracts\Printer as AbstractsPrinter;
use Here\Styles\JsonStyle;
use Here\Styles\VarStyle;
use System\Console\Style\Style;
use System\Text\Str;

final class Printer extends AbstractsPrinter
{
    /** {@inheritdoc} */
    protected function send($out)
    {
        $out = $out === false ? '' : $out;
        (new Style($out))->out(false);
    }

    /** {@inheritdoc} */
    public function info()
    {
        $print = new Style('');
        $this->printInfo($print, $this->content);

        $this->send($print->__toString());
    }

    /** {@inheritdoc} */
    public function dump($var = false)
    {
        $print = new Style('');

        // print header info
        $this->printInfo($print, $this->content);
        // print content
        $this->printSnapshot($print, $this->content, $var);

        $this->send($print->__toString());
    }

    /** {@inheritdoc} */
    public function dumpAll()
    {
        $heres = Here::getHere();
        array_pop($heres);

        foreach ($heres as $here) {
            (new static($here))->dump();
        }
    }

    /** {@inheritdoc} */
    public function count($group)
    {
        $print = new Style();

        // group by file name
        $group_file = [];
        foreach (Here::getHere() as $files) {
            if ($files['group'] === $group) {
                $group_file[$files['file']][] = $files;
            }
        }
        // group by line
        $groups = [];
        foreach ($group_file as $file) {
            foreach ($file as $result) {
                if (in_array($result['line'], $groups)) {
                    continue;
                }
                $groups[] = $result['line'];

                $count = array_filter($file, fn ($item) => $item['line'] === $result['line']);

                $this->printInfo($print, $result, count($count));
                $this->printSnapshot($print, $result);

                $this->send($print->__toString());
            }
        }
    }

    /** {@inheritdoc} */
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

    /** {@inheritdoc} */
    protected function printInfo(&$print, $content, $with_counter = false)
    {
        $print("\n");

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

    /** {@inheritdoc} */
    protected function printSnapshot(&$print, $content, $var = false)
    {
        $print("\n");

        $max_line = ((int) $content['line']) + 3;
        $lenght   = \strlen((string) $max_line);

        /** @var string */
        $file     = $content['file'];
        /** @var array<int, int> */
        $capture  = $content['capture'];
        $snapshot = Here::getCapture($file, $capture);

        foreach ($snapshot as $line => $code) {
            $current = $line === $content['line'];
            $arrow   = $current ? '-> ' : '   ';

            $print($arrow)->textGreen();
            $print->push(Str::fill((string) $line, ' ', $lenght) . ' | ' . $code)->textDim();
            if ($var !== false && $current) {
                $print->push(Str::fill('', ' ', $lenght) . 'var : ')->textLightYellow();

                $print = is_array($var)
                    ? (new JsonStyle($print))->ref($var)->render()
                    : (new VarStyle($print))->ref($var)->render();
            }
            $print->out(false);
        }

        $print('')->out(false);
    }
}
