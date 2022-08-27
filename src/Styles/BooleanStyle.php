<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class BooleanStyle extends VarPrinter
{
    public function render(): Style
    {
        $var  = $this->var;
        $bool = $var == true ? 'true' : 'false';
        $this->style
            ->push($bool)->textYellow()
            ->push(' (')
            ->push('bool')->textLightGreen()
            ->push(')')
            ->new_lines();

        return $this->style;
    }
}
