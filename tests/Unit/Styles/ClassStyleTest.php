<?php

declare(strict_types=1);

use Here\Styles\ClassStyle;
use PHPUnit\Framework\TestCase;
use System\Console\Style\Style;

final class ClaasStyleTest extends TestCase
{
    /** @test */
    public function itCanRenderVariableStyle()
    {
        $style = new Style();
        $var   = new ClassStyle($style);

        $var->ref(now('04-09-2022'));
        $out = $var->render()->__toString();

        $this->assertStringContainsString('2022', $out);
    }
}
