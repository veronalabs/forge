<?php

defined('ABSPATH') || exit;

/**
 * Check if Premium is active.
 *
 * @return bool
 */
function {{PLUGIN_HOOK}}_is_premium_active(): bool
{
    return defined('{{PLUGIN_PREFIX}}_PREMIUM_FILE');
}

/**
 * Display admin notice when Premium is active.
 *
 * @return void
 */
function {{PLUGIN_HOOK}}_premium_active_notice(): void
{
    ?>
    <div class="notice notice-warning">
        <p>
            <strong><?php esc_html_e('{{PLUGIN_NAME}}', '{{PLUGIN_SLUG}}'); ?>:</strong>
            <?php esc_html_e('{{PLUGIN_NAME}} Premium is active and includes all free features. Please deactivate the free version to avoid conflicts.', '{{PLUGIN_SLUG}}'); ?>
        </p>
    </div>
    <?php
}

/**
 * Initialize premium compatibility mode.
 *
 * @param string $pluginFile Main plugin file path.
 * @return void
 */
function {{PLUGIN_HOOK}}_init_premium_compatibility(string $pluginFile): void
{
    add_action('init', function () use ($pluginFile) {
        load_plugin_textdomain(
            '{{PLUGIN_SLUG}}',
            false,
            dirname(plugin_basename($pluginFile)) . '/public/languages'
        );
    }, 1);

    add_action('admin_notices', '{{PLUGIN_HOOK}}_premium_active_notice');

    if (is_multisite()) {
        add_action('network_admin_notices', '{{PLUGIN_HOOK}}_premium_active_notice');
    }
}
