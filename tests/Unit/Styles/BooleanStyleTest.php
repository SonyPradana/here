<?php

declare(strict_types=1);

use Here\Styles\BooleanStyle;
use PHPUnit\Framework\TestCase;
use System\Console\Style\Style;

final class BooleanStyleTest extends TestCase
{
    /** @test */
    public function itCanRenderVariableStyle()
    {
        $style = new Style();
        $var   = new BooleanStyle($style);

        $var->ref(true);
        $out = $var->render()->__toString();

        $this->assertStringContainsString('true', $out);
    }

    /** @test */
    public function itCanRenderVariableStyleUsingClosure()
    {
        $style = new Style();
        $var   = new BooleanStyle($style);

        $var->ref(fn () => true);
        $out = $var->render()->__toString();

        $this->assertStringContainsString('true', $out);
    }
}
