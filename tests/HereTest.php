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
        $capture = [];
        foreach ($dump[0]['capture'] as $line => $code) {
            $capture[$line] = trim($code);
        }

        $this->assertEquals($dump[0]['file'], __DIR__ . '/assets/sample.php');
        $this->assertEquals($dump[0]['line'], 7);
        $this->assertEquals($capture, [
            5 => 'function some_function()',
            6 => '{',
            7 => '// target',
            8 => '}',
        ]);
    }
}
