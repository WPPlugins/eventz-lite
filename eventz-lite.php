<?php
/*
 * @link              http://onebyte.nz
 * @since             1.0.0
 * @package           Eventz Lite
 * @wordpress-plugin
 * Plugin Name:       Eventz Lite
 * Plugin URI:        http://plugin.onebyte.nz
 * Description:       Easily display Eventfinda listings on your web site with a simple shortcode.
 * Version:           1.3.5
 * Author:            onebyte.nz
 * Author URI:        http://onebyte.nz
 * Text Domain:       eventz-lite
 * Domain Path:       /languages
 */
if (!defined('WPINC' )) {
    die();
}
if (!defined('EVENTZ_LITE_VERSION')) {
    define('EVENTZ_LITE_VERSION', '1.3.5');
}
function activate_eventz_lite() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-eventz-activator.php';
    Eventz_Lite_Activator::activate();
}
function deactivate_eventz_lite() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-eventz-deactivator.php';
    Eventz_Lite_Deactivator::deactivate();
}
function check_options_eventz_lite() {
    $version = EVENTZ_LITE_VERSION;
    $option_name = 'plugin_eventz_lite_options';
    $default_options = array('_version'=>$version,'_username'=>'','_password'=>'',
        '_endpoint'=>'api.eventfinda.co.nz','_event_location'=>'1','_event_date'=>'1',
            '_event_category'=>'1','_event_excerpt'=>'300','_event_separator'=>'1',
                '_results_pp'=>'10','_debug'=>'0','_debug_screen'=>'0','_delete_options'=>'0',
                    '_eventfinda_logo'=>'1','_eventfinda_text'=>'0','_show_plugin_logo'=>'0',
                        '_show_plugin_link'=>'0');
    /* Get options if any */
    $old_options = get_option($option_name, array());
    if(!$old_options) {
        /* Not present  - create */
        update_option($option_name, $default_options);
    } else {
        if ($old_options['_version'] !== EVENTZ_LITE_VERSION ) {
            /* Merge with defaults to ensure all required (and new) options present */
            $options = wp_parse_args($old_options, $default_options);
            /* Update */
            update_option($option_name, $options);
        }
    }
}
function run_eventz_lite() {
    $plugin = new Eventz_Lite();
    $plugin->run();  
}
register_activation_hook( __FILE__, 'activate_eventz_lite' );
register_deactivation_hook( __FILE__, 'deactivate_eventz_lite' );
add_action('plugins_loaded', 'check_options_eventz_lite');
require plugin_dir_path( __FILE__ ) . 'includes/class-eventz.php';
run_eventz_lite();

