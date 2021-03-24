<?php
/**
 * Plugin Name: Readonly Update Notifications
 * Description: Show core and plugin updates on a custom updates page, which doesn't allow updating.
 * Version: 1.0.0
 * Plugin URI: https://github.com/jmaekki/readonly-update-notifications
 * Author: Jyri Isomäki
 * Author URI: https://github.com/jmaekki
 * Text Domain: readonly-update-notifications
 * Domain Path: /languages
 * License: GPLv2
 */

namespace JMaekki;

/**
 * Class ReadonlyUpdateNotifications
 */
class ReadonlyUpdateNotifications {

    /**
     * Initialize plugin.
     *
     * @return void
     */
    public static function init() : void {

        // Load plugin translated strings.
        add_action( 'init', [ __CLASS__, 'load_textdomain' ] );

        // Add custom updates page to the menu.
        add_action( 'admin_menu', [ __CLASS__, 'update_notifications_menu' ] );
    }

    /**
     * Load plugin translated strings.
     */
    public static function load_textdomain() : void {
        load_muplugin_textdomain( 'readonly-update-notifications', dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * Get WordPress updates count.
     *
     * @return int
     */
    public static function get_updates_count() : int {
        return static::get_core_updates_count() + count( get_plugin_updates() );
    }

    /**
     * Check if core has updates available.
     *
     * @return int
     */
    public static function get_core_updates_count() : int {
        $core_updates = get_core_updates();

        if ( ! empty( $core_updates ) && ! empty( $core_updates[0]->response && $core_updates[0]->response === 'upgrade' ) ) {
            return 1;
        }

        return 0;
    }

    /**
     * Add custom updates page to the menu.
     *
     * @return void
     */
    public static function update_notifications_menu() {
        $count = static::get_updates_count();

        $notification = '';

        if ( $count ) {
            $notification = "<span class='update-plugins'>{$count}</span>";
        }

        add_dashboard_page( 'Updates', 'Updates ' . $notification, 'activate_plugins', 'readonly_updates', [ __CLASS__, 'render_updates_page' ] );
    }

    /**
     * Display custom updates page.
     *
     * @return void
     */
    public static function render_updates_page() {
        require_once 'templates/readonly-updates.php';
    }
}

ReadonlyUpdateNotifications::init();
