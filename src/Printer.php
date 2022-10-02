<?php

declare(strict_types=1);

namespace Here;

use Here\Abstracts\Printer as AbstractsPrinter;
use Here\Styles\ArrayStyle;
use Here\Styles\VarStyle;
use System\Console\Style\Style;
use System\Text\Str;

final class Printer extends AbstractsPrinter
{
    /**
     * Dump var in end of capture code.
     *
     * @var bool True if print in the end
     */
    private $EOL_var = false;

    /**
     * Dump var in end of capture code.
     *
     * @param bool $EOL_var True if print in the end
     *
     * @return self
     */
    public function printVarEndOfCode($EOL_var)
    {
        $this->EOL_var = $EOL_var;

        return $this;
    }

    /** {@inheritdoc} */
    protected function send($out)
    {
        if (self::$mark_test) {
            return;
        }

        $use_socket = Config::get('socket.enable', false);
        $uri        = (string) Config::get('socket.uri', '127.0.0.1:8080');
        $out        = $out === false ? '' : $out;

        if ($use_socket === false) {
            echo $out;

            return;
        }

        $connector = new \React\Socket\Connector();

        $connector->connect($uri)->then(function (\React\Socket\ConnectionInterface $connection) use ($out) {
            $connection->end($out);
        }, function (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        });
    }

    /** {@inheritdoc} */
    public function info()
    {
        $print = new Style('');
        $this->printInfo($print, $this->content);

        $this->send($print->__toString());
    }

    /** {@inheritdoc} */
    public function dump(...$var)
    {
        $print = new Style('');

        // print header info
        $this->printInfo($print, $this->content);
        // print content
        $this->printSnapshot($print, $this->content, ...$var);

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
        $print->new_lines();

        $print->push(' work ')->textDarkGray()->bgGreen();
        if ($with_counter !== false) {
            $print->push(' ' . $with_counter . 'x')->textDim();
        }
        $print->push(' - ')->textDim();
        // @phpstan-ignore-next-line
        $print->push('`' . $content['file'] . '`')->text_yellow_400();
        $print->push(':')->textDim();
        // @phpstan-ignore-next-line
        $print->push('' . $content['line'])->text_blue_400();
    }

    /** {@inheritdoc} */
    protected function printSnapshot(&$print, $content, ...$var)
    {
        $print->new_lines();

        $count   = count($var);
        $has_var = $count > 0;
        if ($count === 1) {
            $var = $var[0];
        }

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

            $print->push($arrow)->textGreen();
            $print->push(Str::fill((string) $line, ' ', $lenght) . ' | ' . $code)->textDim();
            if ($current
             && $has_var === true
             && $this->EOL_var === false
            ) {
                $this->printVar($print, $var, $lenght);
                continue;
            }
        }

        if ($has_var === true && $this->EOL_var === true) {
            $this->printVar($print, $var, $lenght);
        }
    }

    /**
     * helper, dump variable.
     *
     * @param Style                                      &$style
     * @param string|array<int|string, mixed>|false|null $var
     * @param int                                        $margin_left
     *
     * @return Style
     */
    private function printVar(&$style, $var, $margin_left)
    {
        $style->push(Str::fill('', ' ', $margin_left) . 'var : ')->textLightYellow();

        $tab_size = (int) round($margin_left / 2);
        $style    = is_array($var)
            ? (new ArrayStyle($style))->ref($var)->tabSize($tab_size)->render()
            : (new VarStyle($style))->ref($var)->tabSize($tab_size)->render();

        return $style->new_lines();
    }
}
