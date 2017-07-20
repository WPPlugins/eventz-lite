<?php
/* @link       http://onebyte.nz
 * @author     Craig Sugden - onebyte.nz <info@onebyte.nz>
 * @since      1.0.0
 * @package    Eventz Lite
*/
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
if (!current_user_can( 'activate_plugins')) {
    return;
}
if (!current_user_can('delete_plugins'))  {
    return;
}
$option_name = 'plugin_eventz_lite_options';
$options = get_option($option_name);
if ('1' === $options['_delete_options']) {
    delete_option($option_name);
}