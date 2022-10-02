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

    /** @var int tab indet dept */
    protected $dept = 3;

    /** @var int current line print */
    protected $current_line = 0;

    /** @var int max line to be print */
    protected $max_line = 5;

    /**
     * @param Style $style
     */
    public function __construct($style)
    {
        $this->style    = $style;
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
        $encode = json_encode($var, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

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

    /**
     * Set tab indet dept.
     *
     * @return self
     */
    public function dept(int $dept)
    {
        $this->dept = $dept;

        return $this;
    }

    /**
     * Set current line print.
     *
     * @return self
     */
    public function currentLine(int $current_line)
    {
        $this->current_line = $current_line;

        return $this;
    }

    /**
     * Set max line to be print.
     *
     * @return self
     */
    public function maxLine(int $max_line)
    {
        $this->max_line = $max_line;

        return $this;
    }
}
