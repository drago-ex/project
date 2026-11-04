# Agent Guide

Use this file as package-specific guidance when working in this repository. Keep changes aligned with the existing public API, project conventions, and supported PHP range.

## Package Overview
- **Package**: `drago-ex/project`
- **Type**: project
- **Purpose**: The basis for new projects on Drago Extension.
- **PHP**: >=8.3 <9
- **Runtime dependencies**: nette/robot-loader (^4.1), nette/assets (^1.0), latte/latte (^3.1), tracy/tracy (^2.11), drago-ex/bootstrap (^2.0), drago-ex/project-docker (^1.0), drago-ex/project-front (^3.0)
- **License**: MIT

## Repository Layout
- `app/`: project application configuration and code.
- `assets/`: frontend assets.
- `docker/`, `docker-compose.yaml`: Docker configuration copied from `drago-ex/project-docker` by `drago-install` after `composer install`; not part of this repository.
- `www/`: web root files.
- `.github/`: CI workflows; keep their PHP versions and commands consistent with Composer and the package scripts.
- `composer.json`: package metadata, dependency constraints, autoloading, and available Composer scripts.

## Key Parts
- Error pages: `Error4xxPresenter` renders `Error4xx/<code>.latte` (or `4xx.latte`) inside `Error/@layout.latte`, a minimal layout of its own (charset, noindex), so it does not depend on the modules' layouts; `Error5xxPresenter` logs and prints `500.phtml`. In debug mode Tracy shows the error instead.
- `assets/core/base.js` sets `window.naja`, initializes Naja and Nette forms; `assets/naja/*` are the basic Naja extensions. `npm-loader.js` (postinstall) installs the packages of `vendor/drago-ex/*` that have a `package.json` with `name` and a script or style entry, or with `dependencies` (2026-11-04: project-backend-ui, project-front-ui, project-auth and project-account bring their npm dependencies this way, so no package replaces `package.json`).
- `bin/project-assets` (user, 2026-11-05; composer `post-install-cmd`/`post-update-cmd` as `@php bin/project-assets`) writes `.drago-project/assets.md` from the `## Assets` sections (up to the next `## `) of `readme.md` of every installed drago-ex package (sorted by name) and fails when it cannot write. It is separate from project-setup because project-setup runs in the Docker container, where `app/` and `.drago-project/` belong to root and `compose watch` copies only host → container.
- `bin/project-setup` (user, 2026-11-04) writes no project files (only clears `temp/cache/_config.search`, the production-mode list of found neons) and runs the migrations of the installed packages: checks drago-ex/migration (`Composer\InstalledVersions`), lists the packages with migrations by `Drago\Migration\MigrationFiles::forProject()` (the order and the `migrations/` discovery stay in drago-ex/migration; `migration-priority` is read by it, not by project-tools), checks the connection of the `dibi` section of `local/db.neon` (it does not care which package wrote it), requires a neon under `app/` that registers `MigrationExtension` (`app/Core/Console/console.neon`, copied from drago-ex/migration by drago-install; error otherwise), then runs `vendor/bin/migration db:migrate --packages`; with drago-ex/project-auth and no `admin` in `user_roles` it asks for name, email, password (hidden by `stty` outside Windows, empty name skips, asks again when `vendor/bin/create-user --admin` rejects the input; without a terminal it only prints the create-user command); with drago-ex/project-settings and no `website` row it asks for the name and the description the same way (empty name skips, at most 255 characters) and inserts both rows (`INSERT IGNORE`, so the settings form shows both fields). On a failed connection it only says the connection is not available (user: it cannot know where the database runs). Verified on throwaway projects: no migration package, no `db.neon`, unreachable host, fresh database, second run. A first attempt that also edited layouts and assets by the package READMEs was rejected as hard to maintain.

## Dependencies

Composer constraints describe the package contract. Before changing integration behavior, inspect the source and the actual call sites in this package; do not infer runtime usage solely from dependency declarations.
Development dependencies include phpstan/phpstan-nette (^2.0), phpstan/extension-installer (^1.4), drago-ex/project-tools (^1.0). `phpstan.neon` has no includes: the extension installer includes the extensions of the installed packages (phpstan-nette; drago-ex/database 5.0 has none any more).

## Essential Commands

```bash
composer phpstan                # phpstan analyse
```

## Tests

This repository does not currently define a test script in `composer.json` or contain a `tests/` directory.
Do not claim behavior is covered by tests unless you find and run a relevant suite. When adding a test suite, follow the package’s existing Nette Tester setup if one is present and add an explicit Composer script.

## Installation Hooks

Composer install and update hooks invoke Drago project setup/cleanup commands. Inspect those scripts and their effects before invoking Composer operations that trigger them; they may modify generated project files.

Files copied from extensions (`project-*` packages) belong to the project and are meant to be customized. Updates deliberately do not overwrite them, so never replace them with package versions wholesale; when a package fix is needed, merge only the relevant change into the project file.

## Conventions & Rules

- Preserve backward compatibility unless the task explicitly permits a breaking change. Check callers and public signatures before changing behavior.
- Follow the existing PHP style: strict types, explicit parameter and return types, tabs for indentation, and single-quoted strings where practical. Match nearby code when a convention is unclear.
- Keep package-specific configuration, resources, and migrations in their established directories. Do not edit generated or consuming-project files without confirming they are part of this repository’s intended output.
- Add or update focused tests for behavioral changes when this package has a runnable test setup. Do not invent test commands; use only the scripts and configuration present here.
- Update README examples and Composer metadata when a public API or supported requirement changes.
