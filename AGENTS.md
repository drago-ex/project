# Agent Guide

`drago-ex/project` (type project, PHP >=8.3 <9): the skeleton every Drago application starts from
(`composer create-project`). The workspace rules (code order, code style, READMEs, migrations) are in
`../AGENTS.md`; dependencies are in `composer.json`.

## Layout
- `app/`: `Bootstrap.php`, `app.neon`, `Presentation/Error` (error presenters). Feature packages add their files here.
- `assets/`: `core/base.js` + `base.scss` and the basic Naja extensions in `naja/`. `base.scss` builds Bootstrap in the
  theme of compostrap (`compostrap/src/scss/theme`), so every entry point (admin, website, sign-in, account) has it;
  the errors of a form are an alert of the `@form` widget of drago-ex/form, not a style here. `naja/spinner.js` calls
  `Spinner` of compostrap on the Naja events `start` and `complete`, `spinner.scss` imports its module.
- `bin/project-setup`, `bin/project-assets`: setup scripts (below).
- `npm-loader.js`, `package.json`, `vite.config.js`: frontend build.
- `local/`: configuration of the environment outside git (`db.neon` from project-docker-db).
- `docker/`, `docker-compose.yaml` are copied from drago-ex/project-docker by `drago-install`; they are not part of
  this repository.

## Key Parts
- Error pages: `Error4xxPresenter` renders `Error4xx/<code>.latte` (or `4xx.latte`) inside `Error/@layout.latte`,
  a minimal layout of its own, so it does not depend on the layouts of the modules; `Error5xxPresenter` logs and
  prints `500.phtml`. In debug mode Tracy shows the error instead.
- `assets/core/base.js` sets `window.naja`, initializes Naja and Nette forms and registers ErrorsHandler. It also
  hides the LiveForm message of a field on every `input` event: LiveForm does it only on a keydown, so a pasted or
  autofilled value kept its message until the blur, and that blur on the press of the submit button moved the button
  from under the mouse (the click missed it).
- `npm-loader.js` (npm `postinstall`) installs the packages of `vendor/drago-ex/*` that have a `package.json` with
  `name` and a script or style entry, or with `dependencies`. That is how project-backend-ui, project-front-ui,
  project-auth and project-account bring their npm dependencies; no package replaces the project's `package.json`.
- `bin/project-assets` (composer `post-install-cmd`/`post-update-cmd`) writes `.drago-project/assets.md` from the
  `## Assets` sections (up to the next `## `) of `readme.md` of every installed drago-ex package, sorted by name,
  and fails when it cannot write. It runs on the host: in the Docker image `app/` and `.drago-project/` belong to
  root and `compose watch` copies only host → container. Netis removes it from the scripts.
- `bin/project-setup` sets up the database and writes no project files (it only clears
  `temp/cache/_config.search`). It checks drago-ex/migration, lists the packages with migrations by
  `Drago\Migration\MigrationFiles::forProject()` (order and `migrations/` discovery belong to drago-ex/migration),
  checks the connection of the `dibi` section of `local/db.neon`, requires a neon under `app/` registering
  `MigrationExtension` (`app/Core/Console/console.neon`, copied from drago-ex/migration), then runs
  `vendor/bin/migration db:migrate --packages`. With drago-ex/project-auth and no `admin` in `user_roles` it asks
  for the administrator (an empty name skips; it asks again when `vendor/bin/create-user --admin` rejects the
  input; without a terminal it only prints the command). With drago-ex/project-settings and no `website` row it asks
  for the name and the description and inserts both rows. A failed connection is reported as not available,
  without guessing where the database runs.
- project-setup must not edit layouts, assets or configs by the package READMEs; the user adds those lines.

## README
"Feature Packages" lists every `project-*` package (not the dropped project-install). Update it when a feature
package is added or renamed.

## Rules
- Files copied from `project-*` packages belong to the project and are customized there; updates do not
  overwrite them, so never replace them with package versions wholesale.
- Composer install/update hooks run `drago-install`, `drago-clean` and `bin/project-assets`; they change project
  files, so check what they do before running Composer here.

## Commands
```bash
composer phpstan
```
