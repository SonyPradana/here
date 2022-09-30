<?php

declare(strict_types=1);

use Here\Styles\DefaultStyle;
use PHPUnit\Framework\TestCase;
use System\Console\Style\Style;

final class DefaultStyleTest extends TestCase
{
    /** @test */
    public function itCanRenderVariableStyle()
    {
        $style = new Style();
        $var   = new DefaultStyle($style);

        $var->ref(now('04-09-2022')->__toString());
        $out = $var->render()->__toString();

        $this->assertStringContainsString('2022', $out);
    }
}
