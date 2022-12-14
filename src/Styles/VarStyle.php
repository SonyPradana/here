<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class VarStyle extends VarPrinter
{
    public function render(): Style
    {
        $type = gettype($this->var);

        if ($type === 'string') {
            return (new StringStyle($this->style))
                ->ref($this->var)
                ->currentLine($this->current_line)
                ->tabSize($this->tab_size)
                ->render();
        }

        if ($type === 'integer' | $type === 'double') {
            return (new NumberStyle($this->style))
                ->ref($this->var)
                ->currentLine($this->current_line)
                ->tabSize($this->tab_size)
                ->render();
        }

        if ($type === 'boolean') {
            return (new BooleanStyle($this->style))
                ->ref($this->var)
                ->currentLine($this->current_line)
                ->tabSize($this->tab_size)
                ->render();
        }

        if ($type === 'object') {
            return (new ClassStyle($this->style))
                ->ref($this->var)
                ->currentLine($this->current_line)
                ->tabSize($this->tab_size)
                ->render();
        }

        return (new DefaultStyle($this->style))
            ->ref($this->var)
            ->currentLine($this->current_line)
            ->tabSize($this->tab_size)
            ->render();
    }
}
