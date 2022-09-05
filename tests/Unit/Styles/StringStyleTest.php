<?php

declare(strict_types=1);

use Here\Styles\StringStyle;
use PHPUnit\Framework\TestCase;
use System\Console\Style\Style;

final class StringStyleTest extends TestCase
{
    /** @test */
    public function itCanRenderVariableStyle()
    {
        $style = new Style();
        $var   = new StringStyle($style);

        $var->ref('test');
        $out = $var->render()->__toString();

        $this->assertStringContainsString('test', $out);
    }
}
