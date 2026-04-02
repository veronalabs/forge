# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.0] - 2026-04-02

### Added
- Lazy-loading DI container with singleton pattern
- Service provider interface with register/boot two-phase lifecycle
- Bootstrap class with `plugins_loaded` initialization
- Vite + React 19 admin dashboard with manifest-based PHP enqueue
- ViteHelper for WordPress 6.5+ script module support with fallback
- Admin/frontend SCSS compilation
- Admin/frontend IIFE entry point bundles via Vite
- Unified premium plugin template with symlinks
- Module loader system with manifest.json auto-discovery
- BaseModule abstract for premium modules
- Premium compatibility check (free/premium conflict detection)
- PHPUnit test suite with WordPress test library integration
- wp-scoper configuration for vendor namespace isolation
- Interactive `configure` script with 13 configurable parameters
- Auto-derivation of slug, namespace, prefix, and handles from plugin name

[1.0.0]: https://github.com/veronalabs/forge/releases/tag/v1.0.0
