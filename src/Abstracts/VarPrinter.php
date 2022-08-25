<?php

namespace Here\Abstracts;

use System\Console\Style\Style;

abstract class VarPrinter
{
    /** @var Style */
    protected $style;

    /** @var mixed */
    protected $var;

    /**
     * @param Style $style
     */
    public function __construct($style)
    {
        $this->style = $style;
    }

    /**
     * @param mixed $var Bool var
     *
     * @return self
     */
    public function ref($var)
    {
        $this->var = $var;

        return $this;
    }

    abstract public function render(): Style;
}
