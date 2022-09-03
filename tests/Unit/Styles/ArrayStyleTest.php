<?php

declare(strict_types=1);

use Here\Styles\ArrayStyle;
use PHPUnit\Framework\TestCase;
use System\Console\Style\Style;

final class ArrayStyleTest extends TestCase
{
    /** @test */
    public function itCanRenderVariableStyle()
    {
        $style = new Style();
        $var   = new ArrayStyle($style);

        $var->ref(['one', 'two']);
        $out = $var->render()->__toString();

        $this->assertStringContainsString('one', $out);
        $this->assertStringContainsString('two', $out);
    }
}
