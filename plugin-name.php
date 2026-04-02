<?php
/**
 * Plugin Name: {{PLUGIN_NAME}}
 * Plugin URI: {{PLUGIN_URI}}
 * Description: {{PLUGIN_DESCRIPTION}}
 * Version: {{PLUGIN_VERSION}}
 * Author: VeronaLabs
 * Author URI: https://veronalabs.com/
 * Text Domain: {{PLUGIN_SLUG}}
 * Domain Path: /public/languages
 * Requires at least: {{MIN_WP}}
 * Requires PHP: {{MIN_PHP}}
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 */

defined('ABSPATH') || exit;

/*
|--------------------------------------------------------------------------
| Premium compatibility check
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/src/premium-compatibility.php';

if ({{PLUGIN_HOOK}}_is_premium_active()) {
    {{PLUGIN_HOOK}}_init_premium_compatibility(__FILE__);
    return;
}

/*
|--------------------------------------------------------------------------
| Autoloader
|--------------------------------------------------------------------------
*/

// In production, wp-scoper generates packages/autoload.php.
// In development, Composer's vendor/autoload.php is used instead.
$composerAutoload = __DIR__ . '/packages/autoload.php';
if (!file_exists($composerAutoload)) {
    $composerAutoload = __DIR__ . '/vendor/autoload.php';
}
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/src/constants.php';

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/src/functions.php';

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/
{{PLUGIN_NAMESPACE}}\Bootstrap::init();
