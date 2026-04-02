<?php

namespace {{PLUGIN_NAMESPACE}}\Pro;

use {{PLUGIN_NAMESPACE}}\Pro\Service\Module\ModuleLoader;

defined('ABSPATH') || exit;

/**
 * Premium bootstrap.
 *
 * Registers the premium autoloader, initializes the module loader,
 * and fires the `{{PLUGIN_HOOK}}_premium_loaded` action.
 */
class Bootstrap
{
    /** @var bool Whether premium has already been initialized. */
    private static bool $initialized = false;

    /** @var ModuleLoader|null The module loader instance. */
    private static ?ModuleLoader $moduleLoader = null;

    /**
     * Initialize premium — called on `{{PLUGIN_HOOK}}_loaded` (priority 5).
     *
     * @return void
     */
    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;

        if (!defined('{{PLUGIN_PREFIX}}_PREMIUM_MODULES_DIR')) {
            define('{{PLUGIN_PREFIX}}_PREMIUM_MODULES_DIR', {{PLUGIN_PREFIX}}_PREMIUM_DIR . 'pro/modules/');
        }

        self::registerAutoloader();

        self::$moduleLoader = new ModuleLoader();
        self::$moduleLoader->init();

        /**
         * Fires after premium core is loaded (before modules).
         */
        do_action('{{PLUGIN_HOOK}}_premium_loaded');
    }

    /**
     * Get the module loader instance.
     *
     * @return ModuleLoader|null
     */
    public static function getModuleLoader(): ?ModuleLoader
    {
        return self::$moduleLoader;
    }

    /**
     * Register the PSR-4 autoloader for the premium namespace.
     *
     * @return void
     */
    private static function registerAutoloader(): void
    {
        spl_autoload_register(function (string $class): void {
            $prefix = '{{PLUGIN_NAMESPACE}}\\Pro\\';
            $len = strlen($prefix);

            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relativeClass = substr($class, $len);
            $file = {{PLUGIN_PREFIX}}_PREMIUM_DIR . 'pro/src/' . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });
    }
}
