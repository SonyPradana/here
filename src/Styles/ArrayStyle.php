<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class ArrayStyle extends VarPrinter
{
    public function render(): Style
    {
        $var = $this->sanitize($this->var);

        return $this->style
            ->new_lines()
            ->push($var)->textGreen();
    }
}
