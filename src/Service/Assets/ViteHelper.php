<?php

namespace {{PLUGIN_NAMESPACE}}\Service\Assets;

defined('ABSPATH') || exit;

/**
 * Reads the Vite build manifest and enqueues JS/CSS assets.
 *
 * Supports WordPress 6.5+ script modules (`wp_enqueue_script_module`)
 * with a classic `wp_enqueue_script` fallback for older versions.
 */
class ViteHelper
{
    /**
     * Read and decode the Vite manifest file.
     *
     * Cached per-request to avoid repeated disk reads.
     *
     * @return array|null Decoded manifest array, or null on failure.
     */
    public static function readManifest(): ?array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache === false ? null : $cache;
        }

        $content = @file_get_contents({{PLUGIN_PREFIX}}_DIR . 'public/app/.vite/manifest.json');

        if ($content === false) {
            $cache = false;
            return null;
        }

        $manifest = json_decode($content, true);
        $cache = is_array($manifest) ? $manifest : false;

        return $cache === false ? null : $cache;
    }

    /**
     * Enqueue JS and CSS for a given manifest entry.
     *
     * @param array  $manifest The decoded Vite manifest.
     * @param string $entry    The entry point key (e.g. 'src/main.jsx').
     * @param string $handle   WordPress script/style handle prefix.
     * @return void
     */
    public static function enqueueFromManifest(array $manifest, string $entry, string $handle): void
    {
        if (!isset($manifest[$entry])) {
            return;
        }

        $entryData = $manifest[$entry];
        $distUrl = {{PLUGIN_PREFIX}}_URL . 'public/app/';

        // Enqueue CSS imports.
        if (!empty($entryData['css'])) {
            foreach ($entryData['css'] as $i => $cssFile) {
                wp_enqueue_style(
                    $handle . '-css-' . $i,
                    $distUrl . $cssFile,
                    [],
                    {{PLUGIN_PREFIX}}_VERSION
                );
            }
        }

        // Enqueue JS as ES module (WP 6.5+) or fallback.
        $jsUrl = $distUrl . $entryData['file'];

        if (function_exists('wp_enqueue_script_module')) {
            wp_enqueue_script_module($handle, $jsUrl);
        } else {
            wp_enqueue_script($handle, $jsUrl, [], {{PLUGIN_PREFIX}}_VERSION, true);
        }
    }

    /**
     * Whether the Vite dev server is active.
     *
     * Set `define('{{PLUGIN_PREFIX}}_VITE_DEV', true)` in wp-config.php
     * during local development.
     *
     * @return bool
     */
    public static function isDevServer(): bool
    {
        return defined('{{PLUGIN_PREFIX}}_VITE_DEV') && {{PLUGIN_PREFIX}}_VITE_DEV;
    }
}
