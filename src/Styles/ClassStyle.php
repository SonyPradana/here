<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use ReflectionClass;
use System\Console\Style\Style;

class ClassStyle extends VarPrinter
{
    public function render(): Style
    {
        $obj   = (object) $this->var;
        $class = new ReflectionClass($obj);

        $var = [];
        foreach ($class->getDefaultProperties() as $property => $value) {
            $visible = '';
            if ($class->getProperty($property)->isPublic()) {
                $value   = $class->getProperty($property)->getValue($obj);
                $visible = '+';
            }

            if ($class->getProperty($property)->isPrivate()) {
                $visible = '-';
            }

            if ($class->getProperty($property)->isProtected()) {
                $visible = '#';
            }
            $this->style->new_lines();
            $this->style->repeat(' ', 4);
            $this->style->push($visible)->textYellow();
            $this->style->push($property);
            $this->style->push(': ')->textYellow();

            $var = is_array($value)
                ? new ArrayStyle($this->style)
                : new VarStyle($this->style);

            $this->style = $var->ref($value)->render();
        }

        if ($class->hasMethod('__tostring')) {
            $this->style->new_lines();
            $this->style->repeat(' ', 4);
            $this->style->push('__toString');
            $this->style->push(': ')->textYellow();

            $this->style = (new StringStyle($this->style))->ref($obj->{'__toString'}())->render();
        }

        return $this->style;
    }
}
