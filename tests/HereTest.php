<?php

declare(strict_types=1);

use Here\Here;
use PHPUnit\Framework\TestCase;

final class HereTest extends TestCase
{
    /** @test */
    public function itCanLoadAssetFile()
    {
        $here = new Here('test');
        $here->here(__DIR__ . '/assets/sample.php', 7);

        $dump    = Here::getHere();
        // $capture = array_map(fn ($line) => trim($line), $dump[0]['capture']);
        $capture = Here::getCapture($dump[0]['file'], $dump[0]['capture']);
        $capture = array_map(fn ($code) => trim($code), $capture);

        $this->assertEquals($dump[0]['file'], __DIR__ . '/assets/sample.php');
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
