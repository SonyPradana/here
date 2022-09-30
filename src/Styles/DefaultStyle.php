<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class DefaultStyle extends VarPrinter
{
    public function render(): Style
    {
        $var = $this->sanitize($this->var);
        $this->style
            ->push($var)->textGreen()
            ->new_lines();

        return $this->style;
    }
}
