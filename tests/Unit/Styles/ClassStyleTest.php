<?php

declare(strict_types=1);

use Here\Styles\ClassStyle;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Test;
use System\Console\Style\Style;

final class ClaasStyleTest extends TestCase
{
    /** @test */
    public function itCanRenderVariableStyle()
    {
        $style = new Style();
        $var   = new ClassStyle($style);

        $var->ref(new TestClassStyle());
        $out = $var->render()->__toString();

        $this->assertStringContainsString('2022', $out);
    }
}

class TestClassStyle
{
    /**
     * Public property to scan.
     *
     * @var string
     */
    public $date = '04-09-2022';
}
