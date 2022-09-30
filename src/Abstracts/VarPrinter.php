<?php

declare(strict_types=1);

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
        if (is_callable($var)) {
            $var = call_user_func($var);
        }
        $this->var = $var;

        return $this;
    }

    abstract public function render(): Style;

    /**
     * Sanitize var to save string printed.
     *
     * @param mixed $var Bool var
     *
     * @return string
     */
    public function sanitize($var)
    {
        $encode = json_encode($var, JSON_PRETTY_PRINT);

        if ($encode === false) {
            throw new \Exception('Variable cant be ptrint');
        }

        return $encode;
    }

    /**
     * Get lenght of var.
     *
     * @param mixed $var Bool var
     *
     * @return int
     */
    public function lenght($var)
    {
        /** @var string */
        $str = $var;

        return \strlen($str);
    }
}
