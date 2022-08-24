<?php

namespace Here\Contracts;

use System\Console\Style\Style;

interface StyleInterface
{
    public function render(): Style;
}
