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

## Docker
Docker is set to the minimum configuration for running the project.

If we want to add, for example, MySQL, we add these lines to the dockerfile:
```dockerfile
# php extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
```

And add these lines to  `docker-compose.yml ` to configure the MySQL server as needed:
```yml
# database
db:
  image: library/mariadb:latest
  command: --character-set-server=utf8 --collation-server=utf8_unicode_ci
  restart: always
  environment:
    MYSQL_ROOT_PASSWORD: root
    MYSQL_DATABASE: app
    MYSQL_USER: super
    MYSQL_PASSWORD: pass
    volumes:
      - ./docker/mysql/:/var/lib/mysql
    ports:
      - '6033:3306'
```
As for further configuration on Docker, you can find it in the documentation.

## If we use database, we can use Entity generation
```
composer require drago-ex/generator
```

The configuration is very simple, just copy from the [Drago\Generator](https://github.com/drago-ex/generator) package:  `config.neon `,  `bin\generator ` and  `Bootstrap.php ` and put it all in the bin folder in the project.

Then, just correct the path in the generator to the Bootstrap class, set up a database connection in  `config.neon `

And run the command: `php .\bin\generator app:entity`
