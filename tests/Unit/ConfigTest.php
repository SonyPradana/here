<?php

declare(strict_types=1);

use Here\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    /** @test */
    public function itCanLoadConfig()
    {
        Config::load();

        $this->assertEquals([
            'print.line'    => 2,
            'print.var.end' => false,
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
}
