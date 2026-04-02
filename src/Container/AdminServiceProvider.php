<?php

namespace {{PLUGIN_NAMESPACE}}\Container;

use {{PLUGIN_NAMESPACE}}\Service\Admin\AdminManager;
use {{PLUGIN_NAMESPACE}}\Service\Assets\AssetManager;

defined('ABSPATH') || exit;

/**
 * Admin service provider — registers services used only in wp-admin.
 */
class AdminServiceProvider implements ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(ServiceContainer $container): void
    {
        $container->register('admin', function () {
            return new AdminManager();
        });

        $container->register('assets', function () {
            return new AssetManager();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(ServiceContainer $container): void
    {
        if (is_admin()) {
            $container->get('admin');
            $container->get('assets');
        }
    }
}
