# Drago Project

English | [Čeština](readme.cs.md)

Base project skeleton for Drago Extension applications.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject.svg)](https://badge.fury.io/ph/drago-ex%2Fproject)
[![Coding Style](https://github.com/drago-ex/project/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- Nette Framework
- Composer
- Docker
- Node.js
- Bootstrap
- Naja

## Installation

```bash
composer create-project drago-ex/project
```

## Basic information

Basic package for applications where Bootstrap, Vite, Docker and Naja are already prepared.
You can find all commands in `package.json` like running Docker or Vite.

## Application structure

- `app/Core` contains shared infrastructure and application services.
- `app/Presentation` contains presenters, templates and feature modules.
- `app/Presentation/Accessory` contains reusable presentation helpers and Latte widgets.

## Basic Naja scripts

- **ErrorsHandler** - Handles Naja AJAX errors by displaying user-friendly alert messages based on HTTP status codes. Shows a dismissible Bootstrap alert in the page element with ID `snippet--message`.

- **HyperlinkDisable** - Temporarily disables links with the `data-link-disable` attribute during Naja requests to prevent multiple clicks. Re-enables the links once the request is complete.

- **Spinner** - Displays a full-page spinner during active Naja AJAX requests. Shows the spinner when a request starts and hides it once all requests are complete.

## Available Extensions

Expand the base package with these ready-to-use modules:
- [Docker Setup](https://github.com/drago-ex/project-docker)
- [Database Layer](https://github.com/drago-ex/project-docker-db)
- [User Management](https://github.com/drago-ex/project-user)
- [Authentication](https://github.com/drago-ex/project-auth)
- [Permissions (ACL)](https://github.com/drago-ex/project-permission)
- [Backend Admin](https://github.com/drago-ex/project-backend)
- [Backend UI](https://github.com/drago-ex/project-backend-ui)
- [Application Settings](https://github.com/drago-ex/project-settings)

Each extension documents its required project configuration and post-installation steps in its own README.
When installing the complete project stack, you can use [Project Preset](https://github.com/drago-ex/project-preset) to apply the required setup automatically.

### How extensions work

Extensions are not libraries you call from your code. Each one contains only the essential base of one feature,
and [Project Tools](https://github.com/drago-ex/project-tools) copies its files into your project, where they become
your own code. Every project is different, so adapt the copied files to its needs.

A later `composer update` does not overwrite files that already exist in the project, so your changes are never lost.
This also means that a fix released in an extension does not reach your copies automatically: compare the package
files with your project and take over the changes you want. See
[The Idea Behind Drago Packages](https://github.com/drago-ex/project-tools#the-idea-behind-drago-packages) for details.

## Package Setup

After installing extensions, run the migrations and generate the permission providers of the installed packages:

```bash
php vendor/bin/migration db:migrate --packages
php vendor/bin/create-permission --packages
```

Inside Docker, run them as the web user (`docker compose exec -u www-data server php vendor/bin/...`).
What the tools did is in `.drago-project/`, shown by `vendor/bin/drago-report`.

## Useful Development Tools

- [Database Migrations](https://github.com/drago-ex/migration)
- [Entity & DataForm Generator](https://github.com/drago-ex/generator)
