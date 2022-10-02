<?php

declare(strict_types=1);

namespace Here\Styles;

use Here\Abstracts\VarPrinter;
use Here\Config;
use ReflectionClass;
use ReflectionProperty;
use System\Console\Style\Style;

class ClassStyle extends VarPrinter
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
        $obj   = (object) $this->var;
        $class = new ReflectionClass($obj);

        foreach ($class->getDefaultProperties() as $name => $value) {
            $this->current_line++;
            if ($this->current_line > $this->max_line) {
                $this->style->new_lines()->repeat(' ', 4)->push('...')->textDim();
                break;
            }

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

            $style = is_array($value)
                ? new ArrayStyle($this->style)
                : new VarStyle($this->style);

            $style->ref($value)
                ->tabSize($this->tab_size + 1)
            ;

            $this->style = $style->ref($value)->render();
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
