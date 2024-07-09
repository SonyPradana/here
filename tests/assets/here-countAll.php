<?php

use function Here\here;

here('pass');
// some line code
here('test');
// some line code
here('test');

foreach (range(1, 25) as $line) {
    here('test');
}

// end of line
