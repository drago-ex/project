# Drago Project

Basis for new modules projects on Drago Extension

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
Basic package for applications where the basis for Bootstrap, Vite, Docker, Naja is already prepared.
You can find all commands in `package.json` like running Docker or Vite.

## Basic Naja scripts
- **ErrorsHandler** - Handles Naja AJAX errors by displaying user-friendly alert messages based on HTTP status codes. Shows a dismissible Bootstrap alert in the page element with ID `snippet--message`.

- **HyperlinkDisable** - Temporarily disables links with the `data-link-disable` attribute during Naja requests to prevent multiple clicks. Re-enables the links once the request is complete.

- **Spinner** - Displays a full-page spinner during active Naja AJAX requests. Shows the spinner when a request starts and hides it once all requests are complete.

## We can further expand the package with other basic settings
- https://github.com/drago-ex/project-docker
- https://github.com/drago-ex/project-db
- https://github.com/drago-ex/project-user
- https://github.com/drago-ex/project-auth
- https://github.com/drago-ex/project-permission
- https://github.com/drago-ex/project-backend

## Package Setup Orchestrator

The project includes a specialized tool to automate the initialization of all installed packages. It scans all packages in your `vendor` directory and collects custom commands defined in their `composer.json`.

### Usage
Run the orchestrator:
```bash
php bin/package-setup
```

You can select specific tasks by their number (e.g., `1,3`), run everything sequentially by entering `a`, or quit with `q`.

### How to add commands to your package
To make your package compatible with the orchestrator, add the `drago-project` section to the `extra` part of your `composer.json`:

```json
{
    "extra": {
        "drago-project": {
            "priority": 10,
            "commands": {
                "db:migrate-example": "php vendor/bin/migration db:migrate vendor/vendor-name/package-name/migrations",
                "create:example-class": "php vendor/bin/create-example"
            }
        }
    }
}
```

### Configuration details
- **`commands`**: A key-value list of commands.
    - Use `db:` prefix for database migrations (these are automatically grouped at the top).
    - Use `create:` or other prefixes for code generation or other tasks.
    - *Note: Commands like `create:*-permission` are specialized for generating the base AccessControl permission classes required by the `drago-ex/permission` component.*
- **`priority`**: Controls the execution order (especially important for `Run ALL`).
    - **Lower numbers** have higher priority (run earlier).
    - Use `10` for core packages (Auth, Users).
    - Use `20+` for dependent packages (Permissions, Commerce).
    - Default priority is `100` if not specified.

### Features
- **Intelligent Sorting**: Automatically groups all database migrations at the top while respecting package priorities.
- **Auto-Initialization**: Detects if the migration system itself needs to be initialized and offers a one-click setup.
- **Graceful Failure**: If the database is unreachable, it provides a warning but allows non-database tasks to be listed.

## Running PHP scripts locally with Docker
If you are using Docker for local development, all PHP scripts should be executed inside the PHP container.
```bash
docker compose exec server php path/to/script
```

## Useful tools that come in handy during development
- https://github.com/drago-ex/migration
- https://github.com/drago-ex/generator
