<?php

namespace {{PLUGIN_NAMESPACE}}\Components;

defined('ABSPATH') || exit;

/**
 * Loads PHP view templates from the views/ directory.
 */
class View
{
    /**
     * Load a view file and optionally pass data to it.
     *
     * @param string|array $view  View path(s) relative to views/ (without .php).
     * @param array        $args  Associative array of data extracted into the view scope.
     * @param bool         $return Whether to return the output instead of echoing.
     *
     * @return string|void Rendered HTML when $return is true.
     */
    public static function load($view, array $args = [], bool $return = false)
    {
        $views = is_array($view) ? $view : [$view];

        if ($return) {
            ob_start();
        }

        foreach ($views as $v) {
            $__path = {{PLUGIN_PREFIX}}_DIR . "views/{$v}.php";

            if (!file_exists($__path)) {
                continue;
            }

            if (!empty($args)) {
                extract($args, EXTR_SKIP);
            }

            include $__path;
        }

        if ($return) {
            return ob_get_clean();
        }
    }
}
