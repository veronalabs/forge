# Forge — VeronaLabs Plugin Starter

Craft production-ready WordPress plugins from a battle-tested foundation.

## Quick Start

```bash
composer create-project veronalabs/forge my-plugin
cd my-plugin
./configure
```

The `configure` script will ask for your plugin name, namespace, and other details, then scaffold everything automatically.

## What's Inside

- **Service Container** — Lazy-loading DI with register/boot lifecycle
- **Service Providers** — Two-phase initialization (register factories, then boot)
- **React Dashboard** — Vite + React 19 with manifest-based PHP enqueue
- **SCSS + Entry Points** — Admin/frontend SCSS and IIFE bundles via Vite
- **Premium Template** — Unified premium model with module loader system
- **PHPUnit Tests** — WordPress test library integration
- **wp-scoper** — Vendor namespace isolation for conflict-free distribution

## Architecture

```
my-plugin/
├── src/
│   ├── Bootstrap.php              # Plugin initialization
│   ├── Container/                 # DI container + service providers
│   ├── Components/                # Utilities (View, etc.)
│   └── Service/                   # Business logic (Admin, Assets, etc.)
├── resources/
│   ├── react/src/                 # React dashboard app
│   ├── entries/                   # Admin/frontend IIFE bundles
│   └── scss/                      # Stylesheets
├── views/                         # PHP templates
├── tests/                         # PHPUnit tests
└── premium/                       # Premium plugin template
    └── pro/
        ├── src/                   # Premium bootstrap + module loader
        └── modules/               # Auto-discovered premium modules
```

## Development

```bash
# Install dependencies
composer install
npm install

# Start dev server (React + scripts + SCSS watch)
npm run dev

# Production build
npm run build

# Run tests
composer test
```

## Premium Plugin

The `premium/` directory contains a complete premium plugin template. During `./configure`, it's copied to a sibling directory as `{slug}-premium/`. The premium plugin:

1. Bundles the free plugin via symlinks
2. Boots after the free core via the `{hook}_loaded` action
3. Auto-discovers modules from `pro/modules/` via `manifest.json`

### Creating a Module

```
pro/modules/my-feature/
├── manifest.json
└── src/
    └── MyFeatureModule.php
```

**manifest.json:**
```json
{
    "slug": "my-feature",
    "name": "My Feature",
    "version": "1.0.0",
    "namespace": "MyPlugin\\Pro\\Modules\\MyFeature",
    "main_class": "MyPlugin\\Pro\\Modules\\MyFeature\\MyFeatureModule"
}
```

## License

MIT
