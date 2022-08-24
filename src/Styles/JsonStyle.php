<?php

namespace Here\Styles;

use Here\Contracts\StyleInterface;
use System\Console\Style\Style;

class JsonStyle implements StyleInterface
{
    /** @var Style */
    private $style;

    /** @var array<int|string, mixed> */
    private $var;

    /**
     * @param Style $style
     */
    public function __construct($style)
    {
        $this->style = $style;
    }

    /**
     * @param array<int|string, mixed> $var
     *
     * @return self
     */
    public function ref($var)
    {
        $this->var = $var;

        return $this;
    }

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
