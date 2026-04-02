<?php

namespace {{PLUGIN_NAMESPACE}}\Container;

defined('ABSPATH') || exit;

/**
 * Core service provider — registers services available on every request.
 *
 * Add new services here as features are built.
 */
class CoreServiceProvider implements ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(ServiceContainer $container): void
    {
        // Register core services here as features are built.
    }

    /**
     * {@inheritDoc}
     */
    public function boot(ServiceContainer $container): void
    {
        // Boot core services here.
    }
}
