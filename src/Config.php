<?php

declare(strict_types=1);

namespace Here;

final class Config
{
    /** @var array<string, string|bool|int|null> */
    private static array $configs = [];

    /**
     * Load config for config file.
     * if config not found in root project,
     * it will load config file from package folder.
     *
     * @param string|null $config_file
     *
     * @return void
     */
    public static function load($config_file = null)
    {
        $default_config_file = dirname(__DIR__, 1) . '/here.config.json';
        $user_config_file    = $config_file ?? dirname(__DIR__, 4) . '/here.config.json';

        if (file_exists($user_config_file)) {
            $default_config_file = $user_config_file;
        }

        $config        = file_get_contents($default_config_file);
        $config        = $config === false ? '{}' : $config;
        // @phpstan-ignore-next-line
        self::$configs = json_decode($config, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Save current config to config file.
     *
     * @param array<string, string|bool|int|null> $configs
     *
     * @return void
     */
    private static function save($configs)
    {
        $default_config_file = dirname(__DIR__, 1) . '/here.config.json';
        $user_config_file    = dirname(__DIR__, 4) . '/here.config.json';

        if (file_exists($user_config_file)) {
            $default_config_file = $user_config_file;
        }

        $config = json_encode($configs, JSON_PRETTY_PRINT) . PHP_EOL;

        file_put_contents($default_config_file, $config);
    }

    /**
     * Get config by key.
     *
     * @param string               $key
     * @param string|int|bool|null $default
     *
     * @return string|int|bool|null
     */
    public static function get($key, $default = null)
    {
        if (self::$configs === []) {
            self::load();
        }

        return self::$configs[$key] ?? $default;
    }

    /**
     * Set/create array item of config.
     *
     * @param string               $key
     * @param string|bool|int|null $val
     *
     * @return void
     */
    public static function set($key, $val)
    {
        self::$configs[$key] = $val;

        self::save(self::$configs);
    }

    /**
     * Flush cached config.
     *
     * @return void
     */
    public static function flush()
    {
        self::$configs = [];
    }

    /**
     * Get cached config.
     *
     * @return array<string, string|bool|int|null>
     */
    public static function all()
    {
        return self::$configs;
    }
}
