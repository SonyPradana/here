<?php

namespace Here\Styles;

use Here\Contracts\StyleInterface;
use System\Console\Style\Style;

class VarStyle implements StyleInterface
{
    /** @var Style */
    private $style;

    /** @var string */
    private $var;

    /**
     * @param Style $style
     */
    public function __construct($style)
    {
        $this->style = $style;
    }

    /**
     * @param string $var
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
        $var = $this->var;

        switch (gettype($var)) {
            case 'string':
                $this->style->push('"' . $var . '"')->textYellow();
                break;

            case 'integer':
            case 'double':
                $this->style->push($var)->textBlue();
                break;

            case 'boolean':
                $bool = $var == true ? 'true' : 'false';
                $this->style->push($bool)->textYellow();
                break;

            default:
                $this->style->push($var)->textGreen();
                break;
        }

        $this->style
            ->push(' (')
            ->push(gettype($var) . ':' . strlen((string) $var))->textLightGreen()
            ->push(')')
            ->new_lines();

        return $this->style;
    }
}
