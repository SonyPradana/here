<?php

declare(strict_types=1);

namespace Here;

use Here\Abstracts\Printer;
use ReflectionClass;

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
    public function dump(...$var)
    {
        /** @var string */
        $file     = $this->content['file'];
        /** @var array<int, int> */
        $capture  = $this->content['capture'];
        $snapshot = Here::getCapture($file, $capture);

        $this->sendJson([
            'file'     => $this->content['file'],
            'line'     => $this->content['line'],
            'snapshot' => array_map(fn ($trim) => trim($trim), $snapshot),
            'var'      => $this->encodeVar($var),
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

                // counter
                $count    = array_filter($file, fn ($item) => $item['line'] === $result['line']);

                // build snapshot
                /** @var array<int, int> */
                $capture  = $result['capture'];
                /** @var string */
                $file_name = $result['file'];
                $snapshot  = Here::getCapture($file_name, $capture);

                // send result
                $this->sendJson([
                    'file'     => $result['file'],
                    'line'     => $result['line'],
                    'count'    => $count,
                    'snapshot' => array_map(fn ($trim) => trim($trim), $snapshot),
                ]);
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

    /**
     * Send array to string.
     *
     * @param array<string, array<int, mixed>|int|string> $out
     *
     * @return void
     */
    private function sendJson($out)
    {
        $this->send(json_encode($out, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR, 512));
    }

    /**
     * {@inheritdoc}
     */
    protected function send($out)
    {
        if (self::$mark_test) {
            return;
        }

        $uri = (string) Config::get('socket.uri', '127.0.0.1:8080');
        $out = $out === false ? '' : $out;

        $connector = new \React\Socket\Connector();

        $connector->connect($uri)->then(function (\React\Socket\ConnectionInterface $connection) use ($out) {
            $connection->end(PHP_EOL . $out);
        }, function (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        });
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
    protected function printSnapshot(&$print, $content, ...$var)
    {
    }

    /**
     * Encode variable.
     *
     * @param array<int, mixed> $var
     *
     * @return string
     */
    private function encodeVar($var)
    {
        $var = $var[0];

        $encode = [];
        if (is_callable($var)) {
            $var = call_user_func($var);
        }

        if (is_object($var)) {
            $obj   = (object) $var;
            $class = new ReflectionClass($obj);

            $encode['name'] = $class->name;
            foreach ($class->getDefaultProperties() as $name => $value) {
                $property = $class->getProperty($name);
                $property->setAccessible(true);
                $value = $property->getValue($obj);

                $encode[$name] = $value;
            }
        }

        return json_encode($encode, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR, 512);
    }
}
