<?php

require __DIR__ . '/../vendor/autoload.php';

// Konfigurace aplikace.
$app = new Drago\Configurator;

// Povolení Tracy nástroje.
$app->enableTracy(__DIR__ . '/../log');

// Nastavení časové zóny.
$app->setTimeZone('Europe/Prague');

// Adresář dočasných souborů.
$app->setTempDirectory(__DIR__ . '/../storage');

// Povolení automatického vyhledávání tříd.
$app->addAutoload(__DIR__);

// Vytvoření systémového kontejneru.
$app->addConfig(__DIR__ . '/app.neon');

// Spuštění aplikace.
$app->run();
