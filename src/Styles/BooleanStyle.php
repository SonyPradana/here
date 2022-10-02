<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

final class BooleanStyle extends VarPrinter
{
    public function render(): Style
    {
        $var = $this->sanitize($this->var);

        return $this->style
            ->push($var)->textYellow()
            ->push(' (')
            ->push('bool')->textLightGreen()
            ->push(')');
    }
}
