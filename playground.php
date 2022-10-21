
<?php

use Here\JsonPrinter;
use System\Time\Now;

use function Here\work;

 require_once __DIR__ . '/vendor/autoload.php';

$b = fn() => new Now();

work($b);
