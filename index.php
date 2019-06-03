<?php

declare(strict_types = 1);

// Add composer autoload.
require __DIR__ . '/vendor/autoload.php';

// Call and run application.
App\Bootstrap::boot()
	->run();
