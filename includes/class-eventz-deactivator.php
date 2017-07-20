<?php
/**
 * Fired during plugin deactivation.
 * @link       http://onebyte.nz
 * This class defines all code necessary to run during the plugin's deactivation.
 * @since      1.0.0
 * @package    Eventz Lite
 * @subpackage Eventz/includes
 * @author     Craig Sugden - onebyte.nz <info@onebyte.nz>
 */
class Eventz_Lite_Deactivator {
    /**
     * @since    1.0.0
    */
    public static function deactivate() {
        if (!current_user_can('activate_plugins')) {
            wp_die( __('You do not have sufficient permissions to perform this action.'));
        }
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");
    }
}
