<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class StringStyle extends VarPrinter
{
    public function render(): Style
    {
        /** @var string */
        $var = $this->var;
        $this->style
            ->push('"' . $var . '"')->textYellow()
            ->push(' (')
            ->push('string:' . strlen($var))->textLightGreen()
            ->push(')')
            ->new_lines();

        return $this->style;
    }
}
