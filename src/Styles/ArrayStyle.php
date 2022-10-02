<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use Here\Config;
use System\Console\Style\Style;

class ArrayStyle extends VarPrinter
{
    /**
     * @param Style $style
     */
    public function __construct($style)
    {
        parent::__construct($style);
        $this->max_line = (int) Config::get('print.var.max', 5);
    }

    public function render(): Style
    {
        /** @var array<int|string, mixed> */
        $var = $this->var;

        $this->style
            ->push('array:')->textBlue()
            ->push(count($var))->textBlue()
            ->push(' [')->textYellow()
        ;

        foreach ($var as $key => $value) {
            $this->current_line++;
            if ($this->current_line > $this->max_line) {
                $this->style->new_lines()->repeat(' ', $this->tab_size * 2)->push('...')->textDim();
                break;
            }

            $this->style->new_lines();
            $this->style->repeat(' ', $this->tab_size * 2);
            $this->style->push($key)->textLightGreen();
            $this->style->push(' => ')->textYellow();

            $style = is_array($value)
                ? new ArrayStyle($this->style)
                : new VarStyle($this->style);

            $style->ref($value)
                ->tabSize($this->tab_size + 1)
                ->currentLine($this->current_line)
            ;
            $this->style = $style->render();
        }

        $this->style->new_lines()->repeat(' ', ($this->tab_size * 2) - 2);
        $this->style->push(']')->textYellow();

        return $this->style;
    }
}
