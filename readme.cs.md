# Drago Project

Základní kostra projektu pro aplikace Drago Extension.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject.svg)](https://badge.fury.io/ph/drago-ex%2Fproject)
[![Coding Style](https://github.com/drago-ex/project/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- Nette Framework
- Composer
- Docker
- Node.js
- Bootstrap
- Naja

## Instalace

```bash
composer create-project drago-ex/project
```

## Základní informace

Základní balíček pro aplikace, kde jsou už připravené Bootstrap, Vite, Docker a Naja.
Všechny příkazy, například pro spuštění Dockeru nebo Vite, najdete v `package.json`.

## Struktura aplikace

- `app/Core` obsahuje sdílenou infrastrukturu a služby aplikace.
- `app/Presentation` obsahuje presentery, šablony a moduly jednotlivých funkcí.
- `app/Presentation/Accessory` obsahuje znovupoužitelné pomocníky prezentační vrstvy a widgety Latte.

## Základní skripty pro Naja

- **ErrorsHandler** - Zpracovává chyby AJAXových požadavků Naja a zobrazuje srozumitelné zprávy podle stavového kódu HTTP. Zobrazí zavíratelný Bootstrap alert v elementu s ID `snippet--message`.

- **HyperlinkDisable** - Během požadavků Naja dočasně vypne odkazy s atributem `data-link-disable`, aby se nedalo kliknout vícekrát. Po dokončení požadavku je znovu zapne.

- **Spinner** - Během probíhajících AJAXových požadavků Naja zobrazí spinner přes celou stránku. Ukáže ho při začátku požadavku a skryje po dokončení všech požadavků.

`assets/core/base.js` registruje jen ErrorsHandler; ostatní zaregistrujte tam, kde je používáte, např.
`registerNajaExtensions(HyperlinkDisable, Spinner)` ve vstupním bodě (Spinner potřebuje i `naja/spinner.scss`).

## Dostupná rozšíření

Základní balíček můžete rozšířit o tyto připravené moduly:
- [Docker](https://github.com/drago-ex/project-docker)
- [Databáze](https://github.com/drago-ex/project-docker-db)
- [Správa uživatelů](https://github.com/drago-ex/project-user)
- [Přihlašování](https://github.com/drago-ex/project-auth)
- [Oprávnění (ACL)](https://github.com/drago-ex/project-permission)
- [Administrace](https://github.com/drago-ex/project-backend)
- [Rozhraní administrace](https://github.com/drago-ex/project-backend-ui)
- [Nastavení aplikace](https://github.com/drago-ex/project-settings)

Každé rozšíření popisuje potřebnou konfiguraci projektu a kroky po instalaci ve svém README.
Pro instalaci kompletní sestavy projektu najednou použijte preset, který potřebné nastavení provede automaticky:

- [drago-ex/project-preset](https://github.com/drago-ex/project-preset): kompletní sestava bez oprávnění,
- [drago-ex/project-preset-permission](https://github.com/drago-ex/project-preset-permission): přidá do ní oprávnění
  (menu administrace podle privilegií, tlačítka pro zápis, role z databáze).

### Jak rozšíření fungují

Rozšíření nejsou knihovny, které voláte ze svého kódu. Každé obsahuje jen nezbytný základ jedné funkce
a [Project Tools](https://github.com/drago-ex/project-tools) zkopíruje jeho soubory do projektu, kde se z nich stane
váš vlastní kód. Každý projekt je jiný, takže zkopírované soubory upravte podle jeho potřeb.

Pozdější `composer update` nepřepíše soubory, které už v projektu existují, takže o své úpravy nikdy nepřijdete.
Znamená to ale také, že oprava vydaná v rozšíření se do vašich kopií nedostane sama: porovnejte soubory balíčku
s projektem a převezměte změny, které chcete. Podrobnosti najdete v
[myšlence balíčků Drago](https://github.com/drago-ex/project-tools#the-idea-behind-drago-packages).

## Nastavení balíčků

Po instalaci rozšíření spusťte migrace a příkazy nainstalovaných balíčků (např. providery oprávnění):

```bash
php vendor/bin/migration db:migrate --packages
php vendor/bin/drago-commands
```

V Dockeru je spouštějte jako webového uživatele (`docker compose exec -u www-data server php vendor/bin/...`).
Co nástroje udělaly, je v `.drago-project/` a zobrazí to `vendor/bin/drago-report`.

## Užitečné nástroje pro vývoj

- [Migrace databáze](https://github.com/drago-ex/migration)
- [Generátor entit a datových tříd](https://github.com/drago-ex/generator)
