<?php

declare(strict_types = 1);

// Add composer autoload.
require __DIR__ . '/vendor/autoload.php';

// Run application.
$app = App\Bootstrap::boot();
$app->run();
