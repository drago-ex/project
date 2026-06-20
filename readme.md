# Drago Project

Base project skeleton for Drago Extension applications.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/project/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject.svg)](https://badge.fury.io/ph/drago-ex%2Fproject)
[![Coding Style](https://github.com/drago-ex/project/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project/actions/workflows/coding-style.yml)

## Requirements
- PHP >= 8.3
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

## Package Setup

Project setup commands are handled by [Project Tools](https://github.com/drago-ex/project-tools).
After installing extensions, run `vendor/bin/drago-setup` to execute package-defined setup tasks such as database migrations or generated permission classes.

Inside Docker, run it as the web user:

```bash
docker compose exec -u www-data server php vendor/bin/drago-setup
```

Command definitions and priorities are documented in the packages that provide them.

## Useful Development Tools
- [Database Migrations](https://github.com/drago-ex/migration)
- [Entity & DataForm Generator](https://github.com/drago-ex/generator)
