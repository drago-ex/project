# Drago Project

Base project for Nette applications with Drago Extension: Bootstrap, Naja, Vite and Docker prepared, extended by
ready-made feature packages.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject.svg)](https://badge.fury.io/ph/drago-ex%2Fproject)
[![Coding Style](https://github.com/drago-ex/project/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- Composer
- Node.js
- Docker

## Installation

```bash
composer create-project drago-ex/project
```

```bash
npm install
npm run vite:build
npm run docker:dev
```

`docker:dev` starts the server on `http://127.0.0.1:8080` and keeps copying your changes into the container.
All commands (Docker, Vite) are in `package.json`.

## Application Structure

- `app/Core` contains shared infrastructure and application services.
- `app/Presentation` contains presenters, templates and feature modules.
- `app/Presentation/Accessory` contains reusable presentation helpers and Latte widgets.
- `assets/` contains the entry points of Vite and the basic Naja extensions.
- `local/` holds the configuration of this environment that stays out of git, e.g. the database connection
  in `local/db.neon`.

## Naja Extensions

- **ErrorsHandler** shows an alert with the HTTP error of a failed request in the element `snippet--message`.
- **HyperlinkDisable** disables links with `data-link-disable` while a request runs.
- **Spinner** shows a full-page spinner while requests run (needs `naja/spinner.scss`).

`assets/core/base.js` registers ErrorsHandler; register the others where you use them, e.g.
`registerNajaExtensions(HyperlinkDisable, Spinner)` in an entry point.

## Feature Packages

Each package contains the base of one feature. [Project Tools](https://github.com/drago-ex/project-tools) copies
its files into the project on `composer require`, where they become your own code to adapt. A later
`composer update` does not overwrite them; `vendor/bin/drago-diff` shows what changed in a package.

Infrastructure:
- [Docker Database](https://github.com/drago-ex/project-docker-db): MariaDB in Docker and the connection.
- [Docker](https://github.com/drago-ex/project-docker): the PHP server image (installed with the project).

Website and users:
- [Front](https://github.com/drago-ex/project-front): the front module (installed with the project).
- [Front UI](https://github.com/drago-ex/project-front-ui): the website layout and home page.
- [User](https://github.com/drago-ex/project-user): the signed-in user in presenters and templates.
- [Authentication](https://github.com/drago-ex/project-auth): sign-in, sign-up and password recovery.
- [Account](https://github.com/drago-ex/project-account): the user's own account settings.

Administration:
- [Backend](https://github.com/drago-ex/project-backend): the administration module.
- [Backend UI](https://github.com/drago-ex/project-backend-ui): the administration layout and menu.
- [Settings](https://github.com/drago-ex/project-settings): website name, description and other settings.
- [Permissions](https://github.com/drago-ex/project-permission): users, roles and their permissions.

Content:
- [Blog](https://github.com/drago-ex/project-blog): articles, categories and comments.
- [Static Pages](https://github.com/drago-ex/project-static-page): pages with their own URL and menu item.
- [Gallery](https://github.com/drago-ex/project-gallery): images for the content of articles and pages.
- [Commerce](https://github.com/drago-ex/project-commerce): e-shop administration.

[Project Preset](https://github.com/drago-ex/project-preset) installs the website and the administration with
all of them except commerce, with assets ready for all packages.

## Package Setup

After installing packages with a database, run the migrations:

```bash
docker compose exec -u www-data server php bin/project-setup
```

It runs the migrations of the installed packages and of the project's `migrations/` (only new ones on the next
run). With project-auth and no administrator yet it asks for the administrator account, with project-settings and
no website name yet for the name and the description of the website.

After every `composer install` and `composer update`, `bin/project-assets` writes the `## Assets` sections of
the installed packages into `.drago-project/assets.md`: the lines to add to `assets/admin.js`, `admin.scss`,
`front.js` and `front.scss`.

## Development Tools

- [Migrations](https://github.com/drago-ex/migration)
- [Entity Generator](https://github.com/drago-ex/generator)
