## Drago Project
Basis for new modules projects on Drago Extension

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject.svg)](https://badge.fury.io/ph/drago-ex%2Fproject)
[![Coding Style](https://github.com/drago-ex/project/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/project/badge)](https://www.codefactor.io/repository/github/drago-ex/project)

## Technology
- PHP 8.3 or higher
- composer
- docker
- node.js

## Installation
```
composer create-project drago-ex/project
```

## Basic information
Basic package for applications where the basis for Bootstrap, Vite, Docker, Naja is already prepared.
You can find all commands in `package.json` like running Docker or Vite.

First, run `npm install`

## Database
```sql
CREATE TABLE `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(60) NOT NULL,
    `token` varchar(32) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```
