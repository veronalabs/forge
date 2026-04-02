<?php

namespace {{PLUGIN_NAMESPACE}}\Service\Admin;

use {{PLUGIN_NAMESPACE}}\Components\View;

defined('ABSPATH') || exit;

/**
 * Registers admin menus and renders the React dashboard shell.
 */
class AdminManager
{
    /** @var string Menu slug used for the top-level admin page. */
    const MENU_SLUG = '{{PLUGIN_SHORT}}';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerMenus']);
    }

    /**
     * Add the top-level admin menu.
     *
     * @return void
     */
    public function registerMenus(): void
    {
        add_menu_page(
            __('{{PLUGIN_NAME}}', '{{PLUGIN_SLUG}}'),
            __('{{PLUGIN_NAME}}', '{{PLUGIN_SLUG}}'),
            'manage_options',
            self::MENU_SLUG,
            [$this, 'renderDashboard'],
            'dashicons-admin-generic'
        );

        // Remove the auto-generated submenu item matching the parent.
        remove_submenu_page(self::MENU_SLUG, self::MENU_SLUG);

        // Fire filter so add-ons can hook into menu data.
        apply_filters('{{PLUGIN_HOOK}}_admin_menu_list', []);
    }

    /**
     * Output the mount point for the React dashboard app.
     *
     * @return void
     */
    public function renderDashboard(): void
    {
        View::load('admin/app');
    }
}
