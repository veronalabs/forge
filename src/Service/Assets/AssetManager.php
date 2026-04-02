<?php

namespace {{PLUGIN_NAMESPACE}}\Service\Assets;

defined('ABSPATH') || exit;

/**
 * Enqueues scripts and styles for the admin dashboard.
 *
 * Uses ViteHelper to read the build manifest and enqueue
 * the React app as an ES module (WP 6.5+) or classic script.
 */
class AssetManager
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
    }

    /**
     * Enqueue assets on plugin admin pages only.
     *
     * @param string $hook The current admin page hook suffix.
     * @return void
     */
    public function enqueueAdmin(string $hook): void
    {
        if (!$this->isPluginPage($hook)) {
            return;
        }

        $this->enqueueDashboard();
    }

    /**
     * Enqueue the Vite-built React dashboard app.
     *
     * @return void
     */
    private function enqueueDashboard(): void
    {
        $manifest = ViteHelper::readManifest();

        if (!$manifest) {
            return;
        }

        ViteHelper::enqueueFromManifest($manifest, 'src/main.jsx', '{{PLUGIN_SHORT}}-dashboard');

        wp_print_inline_script_tag(
            'var {{PLUGIN_JS_GLOBAL}} = ' . wp_json_encode($this->getLocalizedData()) . ';',
            ['id' => '{{PLUGIN_SHORT}}-settings-data']
        );
    }

    /**
     * Build the data object exposed to JavaScript.
     *
     * @return array<string, mixed>
     */
    private function getLocalizedData(): array
    {
        return [
            'restUrl'   => rest_url('{{PLUGIN_SHORT}}/v1/'),
            'nonce'     => wp_create_nonce('wp_rest'),
            'version'   => {{PLUGIN_PREFIX}}_VERSION,
            'adminUrl'  => admin_url(),
            'isPremium' => defined('{{PLUGIN_PREFIX}}_PREMIUM_FILE'),
        ];
    }

    /**
     * Check whether the current admin page belongs to this plugin.
     *
     * @param string $hook Admin page hook suffix.
     * @return bool
     */
    private function isPluginPage(string $hook): bool
    {
        return strpos($hook, 'toplevel_page_{{PLUGIN_SHORT}}') !== false
            || strpos($hook, '_page_{{PLUGIN_SHORT}}') !== false;
    }
}
