# Agent guidance – wp-module-link-tracker

This file gives AI agents a quick orientation to the repo. For full detail, see the **docs/** directory.

## What this project is

- **wp-module-link-tracker** – Adds tracking parameters (e.g. UTM) to links. Provides `build_link( $url, $params )` (in `NewfoldLabs\WP\Module\LinkTracker\Functions`) which runs through the filter `nfd_build_url`. On `newfold_container_set` the module registers a `LinkTracker` instance that implements that filter and enqueues a build script. Maintained by Newfold Labs.

- **Stack:** PHP 7.4+. No production Composer dependencies; expects container from host.

- **Architecture:** Hooks `newfold_container_set`, defines `NFD_LINK_TRACKER_BUILD_URL`/`NFD_LINK_TRACKER_BUILD_DIR`, creates `LinkTracker( $container )` and calls `add_hooks()` (admin_enqueue_scripts, filter `nfd_build_url`).

## Key paths

| Purpose | Location |
|---------|----------|
| Bootstrap | `bootstrap.php` |
| Public API | `includes/functions.php` – `build_link()` |
| Filter implementation | `includes/LinkTracker.php` – `build_url()`, enqueue |
| Build | `build/` (JS asset) |
| Tests | `tests/` |

## Essential commands

```bash
composer install
composer run lint
composer run fix
composer run test
```

## Documentation

- **Full documentation** is in **docs/**. Start with **docs/index.md**.
- **CLAUDE.md** is a symlink to this file (AGENTS.md).

---

## Keeping documentation current

When you change code, features, or workflows, update the docs. Keep **docs/index.md** current: when you add, remove, or rename doc files, update the table of contents (and quick links if present). When cutting a release, update **docs/changelog.md**.
