<?php

declare(strict_types=1);

namespace Here;

use Here\Abstracts\Printer as PrinterAbstract;
use System\Collection\Collection;

final class Here
{
    /**
     * Log of debug, containt.
     * - file
     * - line
     * - capture
     * - group.
     *
     * @var array<int, array<string, array<int, mixed>|int|string>>
     */
    private static $info = [];

    /**
     * Dump var in end of capture code.
     *
     * @var bool True if print in the end
     */
    private $EOL_var = false;

    /**
     * cached file.
     *
     * @var array<string, array<int, string>>
     */
    private static $cached_file;

    /**
     * Group name.
     *
     * @var string
     */
    private $group;

    /**
     * New instance.
     *
     * @param string $group Group name use for count by group name
     *
     * @return void
     */
    public function __construct($group = '')
    {
        $this->group = $group;
    }

    /**
     * get loged debug.
     *
     * @return array<int, array<string, array<int, mixed>|int|string>>
     */
    public static function getHere()
    {
        return self::$info;
    }

    /**
     * Register new info.
     *
     * @param array<string, array<int, mixed>|int|string> $info
     *
     * @return array<string, array<int, mixed>|int|string>
     */
    public static function register($info)
    {
        return self::$info[] = $info;
    }

    /**
     * Flush or clear cached info and file conten.
     *
     * @return void
     */
    public static function flush()
    {
        self::$info        = [];
        self::$cached_file = [];
    }

    /**
     * Get content (file) from cache (if exist).
     *
     * @param string $file_name File name and location
     *
     * @return array<int, string>
     */
    public static function getContent($file_name)
    {
        if (!isset(self::$cached_file[$file_name])) {
            self::getFile($file_name);
        }

        return self::$cached_file[$file_name];
    }

    /**
     * Get code snapshot from cached file.
     *
     * @param string          $file_name File name and location
     * @param array<int, int> $lines     Line to capture/snapshot
     *
     * @return array<int, string>
     */
    public static function getCapture($file_name, $lines)
    {
        $content = array_merge([''], self::getContent($file_name));

        /** @var Collection<int, string> */
        $capture = (new Collection($content));

        return $capture->only($lines)->toArray();
    }

    /**
     * Create new printer debug inforamtion.
     *
     * @param string $file       File name
     * @param int    $line       Line
     * @param int    $line_limit Count line to view
     *
     * @return PrinterAbstract
     */
    public function here($file, $line, $line_limit = 3)
    {
        $capture = self::capture($line, $line_limit);

        $info = self::register([
            'file'    => $file,
            'line'    => $line,
            'capture' => $capture,
            'group'   => $this->group,
        ]);

        if (php_sapi_name() === 'cli') {
            return (new Printer($info))->printVarEndOfCode($this->EOL_var);
        }

        return new JsonPrinter($info);
    }

    /**
     * Dump var in end of capture code:.
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

    /**
     * Get file line by lines.
     *
     * @param string $file
     *
     * @return array<int, string>
     */
    private static function getFile($file)
    {
        $cached = isset(self::$cached_file[$file]);
        $exist  = file_exists($file);

        if ($cached) {
            return self::$cached_file[$file];
        }

        if ($exist) {
            $content = \file($file);
            if ($content !== false) {
                return self::$cached_file[$file] = $content;
            }
        }

        throw new \Exception('File not found or alredy use');
    }

    /**
     * capture line of code specific of line.
     *
     * @param int $line
     * @param int $count_line
     *
     * @return array<int, mixed>
     */
    private function capture($line, $count_line = 3)
    {
        $start_capture_line = ($line - $count_line) < 1 ? 1 : ($line - $count_line);
        $end_capture_line   = $line + $count_line;

        return range($start_capture_line, $end_capture_line);
    }
}
