<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

final class StringStyle extends VarPrinter
{
    public function render(): Style
    {
        $lenght = $this->lenght($this->var);
        $var    = $this->sanitize($this->var);

        return $this->style
            ->push($var)->textYellow()
            ->push(' (')
            ->push('string:' . $lenght)->textLightGreen()
            ->push(')');
    }
}
