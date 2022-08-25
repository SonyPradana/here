<?php

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class ArrayStyle extends VarPrinter
{
    public function render(): Style
    {
        $var = json_encode($this->var, JSON_PRETTY_PRINT);
        $var = $var === false ? '{}' : $var;

        return $this->style
            ->new_lines()
            ->push($var)->textGreen()
            ->new_lines();
    }
}
