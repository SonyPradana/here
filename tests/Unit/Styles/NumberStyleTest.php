<?php

declare(strict_types=1);

use Here\Styles\NumberStyle;
use PHPUnit\Framework\TestCase;
use System\Console\Style\Style;

final class NumberStyleTest extends TestCase
{
    /** @test */
    public function itCanRenderVariableStyleInteger()
    {
        $style = new Style();
        $var   = new NumberStyle($style);

        $var->ref(123);
        $out = $var->render()->__toString();

        $this->assertStringContainsString('123', $out);
    }

    /** @test */
    public function itCanRenderVariableStyleFloat()
    {
        $style = new Style();
        $var   = new NumberStyle($style);

        $var->ref(123.456);
        $out = $var->render()->__toString();

        $this->assertStringContainsString('123.456', $out);
    }
}
