<?php

namespace {{PLUGIN_NAMESPACE}}\Pro\Abstracts;

defined('ABSPATH') || exit;

/**
 * Abstract base for premium modules.
 *
 * Each module lives in `pro/modules/<slug>/` with a `manifest.json`
 * and a main class that extends this base. The ModuleLoader calls
 * `init()` which checks requirements, registers hooks, then calls
 * the concrete `setup()`.
 */
abstract class BaseModule
{
    /** @var array The decoded manifest.json data. */
    protected array $manifest;

    /** @var string Absolute path to the module directory. */
    protected string $directory;

    /**
     * @param array  $manifest  Decoded manifest.json contents.
     * @param string $directory Absolute path to the module directory (trailing slash).
     */
    public function __construct(array $manifest, string $directory)
    {
        $this->manifest = $manifest;
        $this->directory = $directory;
    }

    /**
     * Initialize the module — checks requirements then delegates to setup().
     *
     * @return void
     */
    final public function init(): void
    {
        if (!$this->checkRequirements()) {
            return;
        }

        add_filter('{{PLUGIN_HOOK}}_premium_unlocked_features', [$this, 'unlockFeature']);
        add_filter('{{PLUGIN_HOOK}}_premium_module_data', [$this, 'provideModuleData']);

        $this->setup();
    }

    /**
     * Module-specific initialization (hooks, services, etc.).
     *
     * @return void
     */
    abstract protected function setup(): void;

    /**
     * Verify that this module's requirements are met.
     *
     * @return bool True if the module can be loaded.
     */
    protected function checkRequirements(): bool
    {
        if (!empty($this->manifest['dependencies']['{{PLUGIN_SLUG}}'])) {
            $required = $this->manifest['dependencies']['{{PLUGIN_SLUG}}'];
            if (version_compare({{PLUGIN_PREFIX}}_VERSION, $required, '<')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Filter callback — registers this module's slug as an unlocked feature.
     *
     * @param array $features Current unlocked features.
     * @return array
     */
    public function unlockFeature(array $features): array
    {
        $features[$this->getSlug()] = true;
        return $features;
    }

    /**
     * Filter callback — allows the module to expose data to the frontend.
     *
     * @param array $data Current module data.
     * @return array
     */
    public function provideModuleData(array $data): array
    {
        return $data;
    }

    /**
     * @return string Module slug from manifest.
     */
    public function getSlug(): string
    {
        return $this->manifest['slug'] ?? '';
    }

    /**
     * @return string Module display name from manifest.
     */
    public function getName(): string
    {
        return $this->manifest['name'] ?? '';
    }

    /**
     * @return string Module version from manifest.
     */
    public function getVersion(): string
    {
        return $this->manifest['version'] ?? '1.0.0';
    }

    /**
     * @return string Absolute path to the module directory.
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }
}
