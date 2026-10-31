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
- `assets/core/base.js` sets `window.naja`, initializes Naja and Nette forms; `assets/naja/*` are the basic Naja extensions. project-preset replaces `package.json`, so its shared dependencies must follow the ones here.

## Dependencies

Composer constraints describe the package contract. Before changing integration behavior, inspect the source and the actual call sites in this package; do not infer runtime usage solely from dependency declarations.
Development dependencies include phpstan/phpstan-nette (^2.0), phpstan/extension-installer (^1.4), drago-ex/project-tools (^1.0). `phpstan.neon` has no includes: the extension installer includes the extensions of the installed packages (phpstan-nette, drago-ex/database), so a project without drago-ex/database does not fail on a missing file.

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
