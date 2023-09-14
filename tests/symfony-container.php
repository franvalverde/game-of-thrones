<?php

declare(strict_types=1);

use Whalar\Kernel;

require __DIR__.'/bootstrap.php';

$kernel = new Kernel('test', false);
$kernel->boot();

return $kernel->getContainer();
