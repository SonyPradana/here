<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use System\Console\Style\Style;

class ArrayStyle extends VarPrinter
{
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
            $this->style->new_lines();
            $this->style->repeat(' ', $this->dept * 2);
            $this->style->push($key)->textLightGreen();
            $this->style->push(' => ')->textYellow();

            $style = is_array($value)
                ? new ArrayStyle($this->style)
                : new VarStyle($this->style);
            $this->style = $style->ref($value)->dept($this->dept + 1)->render();
        }

        $this->style->new_lines()->repeat(' ', ($this->dept * 2) - 2);
        $this->style->push(']')->textYellow();

        return $this->style;
    }
}
