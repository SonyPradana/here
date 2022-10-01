<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class NumberStyle extends VarPrinter
{
    public function render(): Style
    {
        // $lenght = $this->lenght($this->var);
        $var    = $this->sanitize($this->var);

        return $this->style
            ->push($var)->textBlue()
            ->push(' (')
            ->push(gettype($this->var))->textLightGreen()
            ->push(')');
    }
}
