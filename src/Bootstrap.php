<?php

namespace {{PLUGIN_NAMESPACE}};

use {{PLUGIN_NAMESPACE}}\Container\ServiceContainer;
use {{PLUGIN_NAMESPACE}}\Container\CoreServiceProvider;
use {{PLUGIN_NAMESPACE}}\Container\AdminServiceProvider;
use {{PLUGIN_NAMESPACE}}\Service\Installation\InstallManager;

defined('ABSPATH') || exit;

/**
 * Plugin bootstrap.
 *
 * Initializes the service container, registers lifecycle hooks,
 * and wires up service providers on `plugins_loaded`.
 */
class Bootstrap
{
    /** @var bool Whether the plugin has already been initialized. */
    private static bool $initialized = false;

    /** @var array<class-string<\{{PLUGIN_NAMESPACE}}\Container\ServiceProvider>> Service providers to register. */
    private static array $providers = [
        CoreServiceProvider::class,
        AdminServiceProvider::class,
    ];

    /**
     * Entry point — called once from the main plugin file.
     *
     * Registers activation/deactivation hooks and defers full
     * setup until `plugins_loaded` (priority 10).
     *
     * @return void
     */
    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;

        self::registerLifecycleHooks();

        add_action('plugins_loaded', [__CLASS__, 'setup'], 10);
    }

    /**
     * Run on `plugins_loaded` — loads text domain, boots services,
     * and fires the `{{PLUGIN_HOOK}}_loaded` action.
     *
     * @return void
     */
    public static function setup(): void
    {
        add_action('init', [__CLASS__, 'loadTextdomain']);

        self::initializeServices();

        /**
         * Fires after the plugin is fully loaded.
         *
         * Premium and third-party code should hook here.
         */
        do_action('{{PLUGIN_HOOK}}_loaded');
    }

    /**
     * Return the service container (creates it on first call).
     *
     * @return ServiceContainer
     */
    public static function container(): ServiceContainer
    {
        return ServiceContainer::getInstance();
    }

    /**
     * Shorthand to fetch a service from the container.
     *
     * @param string $id Service identifier.
     * @return mixed|null
     */
    public static function get(string $id)
    {
        return self::container()->get($id);
    }

    /**
     * Load the plugin text domain for i18n.
     *
     * @return void
     */
    public static function loadTextdomain(): void
    {
        load_plugin_textdomain(
            '{{PLUGIN_SLUG}}',
            false,
            dirname(plugin_basename({{PLUGIN_PREFIX}}_MAIN_FILE)) . '/public/languages'
        );
    }

    /**
     * Activation callback.
     *
     * @param bool $networkWide Whether the plugin is being activated network-wide.
     * @return void
     */
    public static function activate(bool $networkWide): void
    {
        InstallManager::activate($networkWide);
    }

    /**
     * Deactivation callback.
     *
     * @return void
     */
    public static function deactivate(): void
    {
        InstallManager::deactivate();
    }

    /**
     * Register activation and deactivation hooks with WordPress.
     *
     * @return void
     */
    private static function registerLifecycleHooks(): void
    {
        register_activation_hook({{PLUGIN_PREFIX}}_MAIN_FILE, [__CLASS__, 'activate']);
        register_deactivation_hook({{PLUGIN_PREFIX}}_MAIN_FILE, [__CLASS__, 'deactivate']);
    }

    /**
     * Instantiate, register, and boot all service providers.
     *
     * @return void
     */
    private static function initializeServices(): void
    {
        $container = self::container();

        $providers = [];
        foreach (self::$providers as $providerClass) {
            $provider = new $providerClass();
            $provider->register($container);
            $providers[] = $provider;
        }

        foreach ($providers as $provider) {
            $provider->boot($container);
        }
    }
}
