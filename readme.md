# Drago Project

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
- `local/` holds the Neon configuration of this environment that stays out of git, e.g. the database connection
  in `local/db.neon`.

## Basic Naja scripts

- **ErrorsHandler** - Handles Naja AJAX errors by displaying user-friendly alert messages based on HTTP status codes. Shows a dismissible Bootstrap alert in the page element with ID `snippet--message`.

- **HyperlinkDisable** - Temporarily disables links with the `data-link-disable` attribute during Naja requests to prevent multiple clicks. Re-enables the links once the request is complete.

- **Spinner** - Displays a full-page spinner during active Naja AJAX requests. Shows the spinner when a request starts and hides it once all requests are complete.

`assets/core/base.js` registers only ErrorsHandler; register the others where you use them, e.g.
`registerNajaExtensions(HyperlinkDisable, Spinner)` in an entry point (Spinner also needs `naja/spinner.scss`).

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
To install the complete project stack at once, use a preset, which applies the required setup automatically:

- [drago-ex/project-preset](https://github.com/drago-ex/project-preset): the complete stack with permissions
  (the ACL of the packages, the administration menu and buttons by privileges, the administration of roles).

### How extensions work

Extensions are not libraries you call from your code. Each one contains only the essential base of one feature,
and [Project Tools](https://github.com/drago-ex/project-tools) copies its files into your project, where they become
your own code. Every project is different, so adapt the copied files to its needs.

A later `composer update` does not overwrite files that already exist in the project, so your changes are never lost.
This also means that a fix released in an extension does not reach your copies automatically: compare the package
files with your project and take over the changes you want. See
[The Idea Behind Drago Packages](https://github.com/drago-ex/project-tools#the-idea-behind-drago-packages) for details.

## Package Setup

After every `composer install` and `composer update`, `bin/project-assets` collects the `## Assets` sections of the
READMEs of the installed packages into `.drago-project/assets.md`, so the lines to add to `assets/admin.js`,
`admin.scss`, `front.js` and `front.scss` are in one place.

After installing extensions, set up their database:

```bash
php bin/project-setup
```

The script checks that [drago-ex/migration](https://github.com/drago-ex/migration) is installed, lists the installed
packages with migrations (when there are none, it only says so), checks that the project has the migration console
(`app/Core/Console/console.neon`, copied from drago-ex/migration by `drago-install`) and the database connection of
`local/db.neon`. Then it runs `php vendor/bin/migration db:migrate --packages`, which runs the migrations of the packages in their
order and the project's own `migrations/`. Running it again runs only the new migrations.
With [drago-ex/project-auth](https://github.com/drago-ex/project-auth) and no administrator yet, it then asks for the
name, email and password of the administrator account in the console (an empty name skips it) and creates it by
`vendor/bin/create-user --admin`, which checks the email and the password. With
[drago-ex/project-settings](https://github.com/drago-ex/project-settings) and no website name yet, it asks for the name
and the description of the website the same way; both can be changed later in the administration.

The commands of the installed packages run with `php vendor/bin/drago-commands`. `bin/project-setup` writes no files
of the project, so with Docker it runs in the container as the web user
(`docker compose exec -u www-data server php bin/project-setup`); Composer and `bin/project-assets` run where the
project files are. What the tools did is in
`.drago-project/`, shown by `vendor/bin/drago-report`.

## Useful Development Tools

- [Database Migrations](https://github.com/drago-ex/migration)
- [Entity & DataForm Generator](https://github.com/drago-ex/generator)
