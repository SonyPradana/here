<?php

declare(strict_types=1);

use Here\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    protected static $config;

    public static function setUpBeforeClass(): void
    {
        self::$config = Config::all();
    }

    public static function tearDownAfterClass(): void
    {
        foreach (self::$config as $key => $config) {
            Config::set($key, $config);
        }
    }

    /** @test */
    public function itCanLoadConfig()
    {
        Config::load();

        $this->assertEquals([
            'print.line'    => 2,
            'print.var.end' => false,
            'socket.enable' => false,
            'socket.uri'    => '127.0.0.1:8080',
        ], Config::all());

        Config::flush();
    }

    /** @test */
    public function itCanLoadConfigUseFileConfig()
    {
        Config::load(dirname(__DIR__, 2) . '/here.config.json');

        $this->assertEquals([
            'print.line'    => 2,
            'print.var.end' => false,
            'socket.enable' => false,
            'socket.uri'    => '127.0.0.1:8080',
        ], Config::all());

        Config::flush();
    }

    /** @test */
    public function itCanGetConfigWithExistConfig()
    {
        Config::load();

        $this->assertEquals(2, Config::get('print.line'));

        Config::flush();
    }

    /** @test */
    public function itCanGetConfigWithNonExistConfig()
    {
        Config::load();

        $this->assertEquals(3, Config::get('row', 3));

        Config::flush();
    }

    /** @test */
    public function itCanLoadAndFlushConfig()
    {
        Config::load();
        Config::flush();

        $this->assertEmpty(Config::all());
    }

    /** @test */
    public function itCanSetExistConfig()
    {
        Config::load();
        // backup
        $backup = Config::get('print.line', 2);

        Config::set('print.line', 5);
        $this->assertEquals(5, Config::get('print.line', 0));

        // reset
        Config::set('print.line', $backup);

        Config::flush();
    }

    /** @test */
    public function itCanSetNewConfig()
    {
        Config::load();

        Config::set('print.test', 5);
        $this->assertEquals(5, Config::get('print.test', 0));

        Config::flush();
    }
}
