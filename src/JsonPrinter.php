<?php

namespace Here;

use Here\Abstracts\Printer;
use System\Console\Style\Style;

class JsonPrinter extends Printer
{
    /** {@inheritdoc} */
    public function info()
    {
        $this->sendJson([
            'file' => $this->content['file'],
            'line' => $this->content['line'],
        ]);
    }

    /** {@inheritdoc} */
    public function dump($var = false)
    {
        /** @var string */
        $file     = $this->content['file'];
        /** @var array<int, int> */
        $capture  = $this->content['capture'];
        $snapshot = Here::getCapture($file, $capture);

        $this->sendJson([
            'file' => $this->content['file'],
        'line'     => $this->content['line'],
        'snapshot' => array_map(fn ($trim) => trim($trim), $snapshot),
        ]);
    }

    /** {@inheritdoc} */
    public function dumpAll()
    {
        $heres = Here::getHere();
        array_pop($heres);

        foreach ($heres as $here) {
            (new self($here))->dump();
        }
    }

    /** {@inheritdoc} */
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

        /** @var string */
        $file     = $last_here['file'];
        /** @var array<int, int> */
        $capture  = $last_here['capture'];
        $snapshot = Here::getCapture($file, $capture);

        $this->sendJson([
            'file'     => $last_here['file'],
            'line'     => $last_here['line'],
            'count'    => $count,
            'snapshot' => array_map(fn ($trim) => trim($trim), $snapshot),
        ]);
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

    /**
     * Send array to string.
     *
     * @param array<string, array<int, mixed>|int|string> $out
     *
     * @return void
     */
    private function sendJson($out)
    {
        $this->send(json_encode($out));
    }

    /**
     * {@inheritdoc}
     */
    protected function send($out)
    {
        $out = $out === false ? '' : $out;
        (new Style($out))->out(false);
    }

    /**
     * {@inheritdoc}
     */
    protected function printInfo(&$print, $content, $with_counter = false)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function printSnapshot(&$print, $content, $var = false)
    {
    }
}