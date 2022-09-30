<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class NumberStyle extends VarPrinter
{
    public function render(): Style
    {
        $lenght = $this->lenght($this->var);
        $var    = $this->sanitize($this->var);
        $this->style
            ->push($var)->textBlue()
            ->push(' (')
            ->push(gettype($this->var) . ':' . $lenght)->textLightGreen()
            ->push(')')
            ->new_lines();

        return $this->style;
    }
}
