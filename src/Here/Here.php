<?php

declare(strict_types=1);

namespace Here;

final class Here
{
    /**
     * Log of debug, containt.
     * - file
     * - line
     * - content
     * - capture
     * - group.
     *
     * @var array<int, array<string, array<int, mixed>|int|string>>
     */
    private static $info = [];

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
     * Create new printer debug inforamtion.
     *
     * @param string $file File name
     * @param int    $line Line
     *
     * @return Printer
     */
    public function here($file, $line)
    {
        $content = self::getFile($file);
        $capture = self::capture($content, $line);

        $info = self::$info[] = [
            'file'    => $file,
            'line'    => $line,
            'content' => $content,
            'capture' => $capture,
            'group'   => $this->group,
        ];

        return new Printer($info);
    }

    /**
     * Get file line by lines.
     *
     * @param string $file
     *
     * @return array<int, string>
     */
    private function getFile($file)
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
     * @param array<int, string> $content
     * @param int                $line
     *
     * @return array<int, mixed>
     */
    private function capture($content, $line)
    {
        $total_line         = count($content);
        $start_capture_line = ($line - 3) < 1 ? 1 : ($line - 3);
        $end_capture_line   = ($line + 3) > $total_line ? $total_line : ($line + 3);
        $capture            = [];

        for ($line = $start_capture_line; $line < $end_capture_line; $line++) {
            $capture[$line + 1] = $content[$line];
        }

        return $capture;
    }
}
