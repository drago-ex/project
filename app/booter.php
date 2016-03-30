<?php

/**
 * This file is part of the Drago Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
require __DIR__ . '/../vendor/autoload.php';

// Configure application.
$app = new Drago\Configurator();

// Enable debagger bar.
$app->enableDebugger(__DIR__ . '/../log');

// Temporary directory.
$app->setTempDirectory(__DIR__ . '/../storage');

// Enabled autoload classes.
$app->addAutoload(__DIR__);

// Create DI container from configuration files.
$app->addFindConfig(__DIR__ . '/modules');
$app->addConfig(__DIR__ . '/app.neon');

// Run application.
$app->run();
