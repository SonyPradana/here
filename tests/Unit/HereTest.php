<?php

declare(strict_types=1);

use Here\Here;
use PHPUnit\Framework\TestCase;

final class HereTest extends TestCase
{
    /** @test */
    public function itCanGetAllRegisterHere()
    {
        $here = new Here('test');
        $here->here(dirname(__DIR__, 1) . '/assets/sample.php', 1);
        $here->here(dirname(__DIR__, 1) . '/assets/sample.php', 3);
        $here->here(dirname(__DIR__, 1) . '/assets/sample.php', 7);

        $all = Here::getHere();

        $this->assertEquals([
            'file'    => dirname(__DIR__, 1) . '/assets/sample.php',
            'line'    => 1,
            'capture' => [0=>1, 1=>2, 2=>3, 3=>4],
            'group'   => 'test',
        ], $all[0]);

        $this->assertEquals([
            'file'    => dirname(__DIR__, 1) . '/assets/sample.php',
            'line'    => 3,
            'capture' => [0=>1, 1=>2, 2=>3, 3=>4, 4=>5, 5=>6],
            'group'   => 'test',
        ], $all[1]);

        $this->assertEquals([
            'file'    => dirname(__DIR__, 1) . '/assets/sample.php',
            'line'    => 7,
            'capture' => [0=>4, 1=>5, 2=>6, 3=>7, 4=>8, 5=>9, 6=>10],
            'group'   => 'test',
        ], $all[2]);

        Here::flush();
    }

    /** @test */
    public function itCanRegisterUsingInfo()
    {
        Here::register([
            'file'    => dirname(__DIR__, 1) . '/assets/sample.php',
            'line'    => 1,
            'capture' => [0=>1, 1=>2, 2=>3, 3=>4],
            'group'   => 'test',
        ]);

        $all = Here::getHere();

        $this->assertEquals([
            'file'    => dirname(__DIR__, 1) . '/assets/sample.php',
            'line'    => 1,
            'capture' => [0=>1, 1=>2, 2=>3, 3=>4],
            'group'   => 'test',
        ], $all[0]);

        Here::flush();
    }

    /** @test */
    public function itCanFlushHere()
    {
        Here::register([
            'file'    => dirname(__DIR__, 1) . '/assets/sample.php',
            'line'    => 1,
            'capture' => [0=>1, 1=>2, 2=>3, 3=>4],
            'group'   => 'test',
        ]);

        $this->assertCount(1, Here::getHere());
        Here::flush();
        $this->assertCount(0, Here::getHere());
    }

    /** @test */
    public function itCanGetFileContent()
    {
        $file = Here::getContent(dirname(__DIR__, 1) . '/assets/sample.php');

        $this->assertNotEmpty($file);

        Here::flush();
    }

    /** @test */
    public function itCanThrowExceptionWhenFileNotExists()
    {
        $this->expectExceptionMessage('File not found or alredy use');

        Here::getContent(dirname(__DIR__, 1) . '/assets/sample2.php');

        Here::flush();
    }

    /** @test */
    public function itCanCaptureCopdeSnapshot()
    {
        $info = [
            'file'    => dirname(__DIR__, 1) . '/assets/sample.php',
            'line'    => 1,
            'capture' => [0=>1, 1=>2, 2=>3, 3=>4],
            'group'   => 'test',
        ];

        Here::register($info);

        $capture = Here::getCapture($info['file'], $info['capture']);
        $capture = array_map(fn ($code) => trim($code), $capture);

        $this->assertEquals([
            1 => '<?php',
            2 => '',
            3 => 'some_function();',
            4 => '',
        ], $capture);

        Here::flush();
    }

    /** @test */
    public function itCanRenderHere()
    {
        $here = new Here('test');
        $here->here(dirname(__DIR__, 1) . '/assets/sample.php', 7);

        $dump    = Here::getHere();
        $capture = Here::getCapture($dump[0]['file'], $dump[0]['capture']);
        $capture = array_map(fn ($code) => trim($code), $capture);

        $this->assertEquals($dump[0]['file'], dirname(__DIR__, 1) . '/assets/sample.php');
        $this->assertEquals($dump[0]['line'], 7);
        $this->assertEquals($capture, [
            4 => '',
            5 => 'function some_function()',
            6 => '{',
            7 => '// target',
            8 => '}',
        ]);
    }
}
