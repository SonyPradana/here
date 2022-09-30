<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class VarStyle extends VarPrinter
{
    /** @var bool Cek variable is closure */
    private $is_closure;

    public function ref($var)
    {
        $this->is_closure = is_callable($var);

        return parent::ref($var);
    }

    public function render(): Style
    {
        $type = gettype($this->var);

        if ($type === 'string') {
            return (new StringStyle($this->style))->ref($this->var)->render();
        }

        if ($type === 'integer' | $type === 'double') {
            return (new NumberStyle($this->style))->ref($this->var)->render();
        }

        if ($type === 'boolean') {
            return (new BooleanStyle($this->style))->ref($this->var)->render();
        }

        if ($this->is_closure) {
            return (new DefaultStyle($this->style))->ref('Closure')->render();
        }

        return (new ClassStyle($this->style))->ref($this->var)->render();
    }
}
