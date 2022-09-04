<?php

use function Here\here;

// some line code
here()->dumpIf(fn () => true, 'this must show on your console');
// some line code

here()->dumpIf(fn () => false, 'this must not show on your console');
// end of line
