<?php

namespace {{PLUGIN_NAMESPACE}}\Pro\Service\Module;

use {{PLUGIN_NAMESPACE}}\Pro\Abstracts\BaseModule;

defined('ABSPATH') || exit;

/**
 * Discovers and loads premium modules from the modules directory.
 *
 * On `init` (priority 20), scans `pro/modules/` for subdirectories
 * containing a `manifest.json`, registers per-module PSR-4 autoloaders,
 * and instantiates each module's main class.
 */
class ModuleLoader
{
    /** @var array<string, BaseModule> Loaded module instances keyed by slug. */
    private array $loadedModules = [];

    /** @var array<string, callable> Registered autoloaders keyed by namespace. */
    private array $autoloaders = [];

    /** @var array<string, array> Cached manifest data keyed by file path. */
    private array $manifests = [];

    /**
     * Register the module loading hook.
     *
     * @return void
     */
    public function init(): void
    {
        add_action('init', [$this, 'loadModules'], 20);
    }

    /**
     * Scan the modules directory and load each module.
     *
     * @return void
     */
    public function loadModules(): void
    {
        $modulesDir = {{PLUGIN_PREFIX}}_PREMIUM_MODULES_DIR;

        if (!is_dir($modulesDir)) {
            return;
        }

        $moduleDirs = glob($modulesDir . '*', GLOB_ONLYDIR);

        if (!$moduleDirs) {
            return;
        }

        foreach ($moduleDirs as $moduleDir) {
            $this->loadModule(basename($moduleDir), $moduleDir);
        }

        /**
         * Fires after all premium modules have been loaded.
         *
         * @param string[] $slugs List of loaded module slugs.
         */
        do_action('{{PLUGIN_HOOK}}_premium_modules_loaded', array_keys($this->loadedModules));
    }

    /**
     * Load a single module by slug and directory path.
     *
     * @param string $slug Module slug (directory name).
     * @param string $dir  Absolute path to the module directory.
     * @return bool True if the module was loaded successfully.
     */
    public function loadModule(string $slug, string $dir): bool
    {
        if (isset($this->loadedModules[$slug])) {
            return true;
        }

        $manifestPath = $dir . '/manifest.json';

        if (!file_exists($manifestPath)) {
            return false;
        }

        $manifest = $this->loadManifest($manifestPath);

        if (empty($manifest)) {
            return false;
        }

        $this->registerModuleAutoloader($manifest, $dir . '/src');

        $mainClass = $manifest['main_class'] ?? null;

        if (!$mainClass || !class_exists($mainClass)) {
            return false;
        }

        $module = new $mainClass($manifest, trailingslashit($dir));

        if (!$module instanceof BaseModule) {
            return false;
        }

        $module->init();
        $this->loadedModules[$slug] = $module;

        /**
         * Fires after a single premium module is loaded.
         *
         * @param string     $slug   Module slug.
         * @param BaseModule $module The module instance.
         */
        do_action('{{PLUGIN_HOOK}}_premium_module_loaded', $slug, $module);

        return true;
    }

    /**
     * Register a PSR-4 autoloader for a module's namespace.
     *
     * @param array  $manifest The module manifest.
     * @param string $baseDir  Absolute path to the module's `src/` directory.
     * @return void
     */
    private function registerModuleAutoloader(array $manifest, string $baseDir): void
    {
        $namespace = $manifest['namespace'] ?? null;

        if (!$namespace) {
            return;
        }

        $namespace = rtrim($namespace, '\\') . '\\';
        $baseDir = trailingslashit($baseDir);

        if (isset($this->autoloaders[$namespace])) {
            return;
        }

        $autoloader = function (string $class) use ($namespace, $baseDir): void {
            $len = strlen($namespace);
            if (strncmp($namespace, $class, $len) !== 0) {
                return;
            }

            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        };

        spl_autoload_register($autoloader);
        $this->autoloaders[$namespace] = $autoloader;
    }

    /**
     * Read and cache a manifest.json file.
     *
     * @param string $path Absolute path to manifest.json.
     * @return array Decoded manifest, or empty array on failure.
     */
    private function loadManifest(string $path): array
    {
        if (isset($this->manifests[$path])) {
            return $this->manifests[$path];
        }

        $content = file_get_contents($path);
        $manifest = json_decode($content, true);

        if (!is_array($manifest)) {
            return [];
        }

        $this->manifests[$path] = $manifest;
        return $manifest;
    }

    /**
     * @return string[] List of loaded module slugs.
     */
    public function getLoadedModules(): array
    {
        return array_keys($this->loadedModules);
    }

    /**
     * @return array<string, BaseModule> All loaded module instances.
     */
    public function getModules(): array
    {
        return $this->loadedModules;
    }

    /**
     * @param string $slug Module slug.
     * @return BaseModule|null
     */
    public function getModule(string $slug): ?BaseModule
    {
        return $this->loadedModules[$slug] ?? null;
    }

    /**
     * @param string $slug Module slug.
     * @return bool
     */
    public function isModuleLoaded(string $slug): bool
    {
        return isset($this->loadedModules[$slug]);
    }
}
