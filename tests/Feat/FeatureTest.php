<?php

use Here\Here;
use Here\JsonPrinter;
use Here\Printer;
use PHPUnit\Framework\TestCase;

final class FeatureTest extends TestCase
{
    protected function setUp(): void
    {
        Printer::markAsTest();
        JsonPrinter::markAsTest();
    }

    protected function tearDown(): void
    {
        Here::flush();
        Printer::markAsTest(false);
        JsonPrinter::markAsTest(false);
    }

    /** @test */
    public function itCanRenderInfo()
    {
        require_once dirname(__DIR__, 1) . '/assets/here-info.php';

        $dump = Here::getHere();

        $this->assertEquals(6, $dump[0]['line']);
        $this->assertEquals([4, 5, 6, 7, 8], $dump[0]['capture']);
    }

    /** @test */
    public function itCanRenderDump()
    {
        require_once dirname(__DIR__, 1) . '/assets/here-dump.php';

        $dump = Here::getHere();

        $this->assertEquals(6, $dump[0]['line']);
        $this->assertEquals([4, 5, 6, 7, 8], $dump[0]['capture']);
    }

    /** @test */
    public function itCanRenderDumpAll()
    {
        require_once dirname(__DIR__, 1) . '/assets/here-dumpAll.php';

        $dump = Here::getHere();

        $this->assertEquals(6, $dump[0]['line']);
        $this->assertEquals([4, 5, 6, 7, 8], $dump[0]['capture']);
    }

    /** @test */
    public function itCanRenderCount()
    {
        require_once dirname(__DIR__, 1) . '/assets/here-count.php';

        $items = array_filter(Here::getHere(), fn ($item) => $item['group'] === 'test');
        $count = count($items);

        $this->assertEquals(27, $count);
    }

    /** @test */
    public function itCanRenderCountAll()
    {
        require_once dirname(__DIR__, 1) . '/assets/here-countAll.php';

        $all = Here::getHere();

        // count 'test' group
        $items = array_filter($all, fn ($item) => $item['group'] === 'test');
        $count = count($items);
        // count 'pass' group
        $items2 = array_filter($all, fn ($item) => $item['group'] === 'pass');
        $count2 = count($items2);

        $this->assertEquals(27, $count);
        $this->assertEquals(1, $count2);
    }

    /** @test */
    public function itCanRenderDumpIf()
    {
        require_once dirname(__DIR__, 1) . '/assets/here-dumpIf.php';

        $dump = Here::getHere();

        $this->assertEquals(6, $dump[0]['line']);
        $this->assertEquals([4, 5, 6, 7, 8], $dump[0]['capture']);
    }
}
