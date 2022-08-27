<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class NumberStyle extends VarPrinter
{
    public function render(): Style
    {
        /** @var string|int $var */
        $var = $this->var;
        $this->style
            ->push($var)->textBlue()
            ->push(' (')
            ->push(gettype($var) . ':' . strlen((string) $var))->textLightGreen()
            ->push(')')
            ->new_lines();

        return $this->style;
    }
}
