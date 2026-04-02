<?php
/**
 * Plugin Name: {{PLUGIN_NAME}} Premium
 * Plugin URI: {{PLUGIN_URI}}
 * Description: Premium features for {{PLUGIN_NAME}} — advanced modules, integrations, and more.
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
| Premium Constants
|--------------------------------------------------------------------------
*/
define('{{PLUGIN_PREFIX}}_PREMIUM_FILE', __FILE__);
define('{{PLUGIN_PREFIX}}_PREMIUM_DIR', plugin_dir_path(__FILE__));
define('{{PLUGIN_PREFIX}}_PREMIUM_URL', plugin_dir_url(__FILE__));

/*
|--------------------------------------------------------------------------
| Override Free Constants (before loading constants.php)
|--------------------------------------------------------------------------
| Assets are served from the premium directory.
*/
if (!defined('{{PLUGIN_PREFIX}}_URL')) {
    define('{{PLUGIN_PREFIX}}_URL', {{PLUGIN_PREFIX}}_PREMIUM_URL);
}

if (!defined('{{PLUGIN_PREFIX}}_DIR')) {
    define('{{PLUGIN_PREFIX}}_DIR', {{PLUGIN_PREFIX}}_PREMIUM_DIR);
}

if (!defined('{{PLUGIN_PREFIX}}_MAIN_FILE')) {
    define('{{PLUGIN_PREFIX}}_MAIN_FILE', __FILE__);
}

/*
|--------------------------------------------------------------------------
| Load Free Core (via symlinks)
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

require_once __DIR__ . '/src/constants.php';
require_once __DIR__ . '/src/functions.php';

// Initialize free core.
{{PLUGIN_NAMESPACE}}\Bootstrap::init();

/*
|--------------------------------------------------------------------------
| Load Premium
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/pro/src/Bootstrap.php';

add_action('{{PLUGIN_HOOK}}_loaded', function () {
    {{PLUGIN_NAMESPACE}}\Pro\Bootstrap::init();
}, 5);
