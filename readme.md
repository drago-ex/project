## Drago Project
Basis for new modules projects on Drago Extension

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject.svg)](https://badge.fury.io/ph/drago-ex%2Fproject)
[![Coding Style](https://github.com/drago-ex/project/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/project/badge)](https://www.codefactor.io/repository/github/drago-ex/project)

## Requirements
- PHP >= 8.3
- Nette Framework
- Composer
- Docker
- Node.js

## Installation
```bash
composer create-project drago-ex/project
```

## Basic information
Basic package for applications where the basis for Bootstrap, Vite, Docker, Naja is already prepared.
You can find all commands in `package.json` like running Docker or Vite.

## Basic Naja scripts
- **ErrorsExtension** – Handles Naja AJAX errors by displaying user-friendly alert messages based on HTTP status codes. Shows a dismissible Bootstrap alert in the page element with ID `snippet--message`.
- 
- **HyperlinkDisable** – Temporarily disables links with the data-link-disable attribute during Naja requests to prevent multiple clicks. Re-enables the links once the request is complete.
- 
- **SpinnerExtension** – Displays a full-page spinner during active Naja AJAX requests. Shows the spinner when a request starts and hides it once all requests are complete.

## We can further expand the package with other basic settings
- https://github.com/drago-ex/project-docker
- https://github.com/drago-ex/project-db
- https://github.com/drago-ex/project-user
- https://github.com/drago-ex/project-auth

## Useful tools that come in handy during development
- https://github.com/drago-ex/migration
- https://github.com/drago-ex/generator


