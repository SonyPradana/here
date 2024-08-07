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

        $var->ref((new DateTime('04-09-2022'))->format('Y-m-d H:i:s'));
        $out = $var->render()->__toString();

        $this->assertStringContainsString('2022', $out);
    }
}
