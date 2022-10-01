<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use ReflectionClass;
use ReflectionProperty;
use System\Console\Style\Style;

class ClassStyle extends VarPrinter
{
    public function render(): Style
    {
        $obj   = (object) $this->var;
        $class = new ReflectionClass($obj);

        foreach ($class->getDefaultProperties() as $name => $value) {
            $visible  = '';
            $property = $class->getProperty($name);
            $property->setAccessible(true);
            $value = $property->getValue($obj);

            switch ($property->getModifiers()) {
                case ReflectionProperty::IS_PUBLIC:
                    $visible = '+';
                    break;

                case ReflectionProperty::IS_PRIVATE:
                    $visible = '-';
                    break;

                case ReflectionProperty::IS_PROTECTED:
                    $visible = '#';
                    break;
            }

            $this->style->new_lines();
            $this->style->repeat(' ', 4);
            $this->style->push($visible)->textYellow();
            $this->style->push($name);
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
