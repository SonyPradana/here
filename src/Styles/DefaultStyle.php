<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

final class DefaultStyle extends VarPrinter
{
    public function render(): Style
    {
        $var = $this->sanitize($this->var);

        return $this->style->push($var)->textGreen();
    }
}
