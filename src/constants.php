<?php

defined('ABSPATH') || exit;

$pluginDir = dirname(__DIR__);

if (!defined('{{PLUGIN_PREFIX}}_VERSION')) {
    define('{{PLUGIN_PREFIX}}_VERSION', '{{PLUGIN_VERSION}}');
}

if (!defined('{{PLUGIN_PREFIX}}_URL')) {
    define('{{PLUGIN_PREFIX}}_URL', plugin_dir_url($pluginDir . '/{{PLUGIN_SLUG}}.php'));
}

if (!defined('{{PLUGIN_PREFIX}}_DIR')) {
    define('{{PLUGIN_PREFIX}}_DIR', $pluginDir . '/');
}

if (!defined('{{PLUGIN_PREFIX}}_MAIN_FILE')) {
    define('{{PLUGIN_PREFIX}}_MAIN_FILE', {{PLUGIN_PREFIX}}_DIR . '{{PLUGIN_SLUG}}.php');
}

if (!defined('{{PLUGIN_PREFIX}}_SITE')) {
    define('{{PLUGIN_PREFIX}}_SITE', '{{PLUGIN_URI}}');
}

unset($pluginDir);
