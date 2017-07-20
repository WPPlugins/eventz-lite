<?php
/**
 * Class for dubugging
 * @link       http://onebyte.nz
 * @since      1.2.0
 * @package    Eventz Lite
 * @subpackage Eventz/includes
 * @author     onebyte.nz <info@onebyte.nz>
 */
class Eventz_Lite_Debug {
    public $msg;    
    public function __construct($debug_errors, $debug_screen, $debug_event) {
        if ($debug_errors) {self::write_log('EVENTZ LITE ' . $debug_event);}
        $this->msg = __('We could not complete your request due to an error', 'eventz-lite') .
            '.<br>' . __('Please try again later', 'eventz-lite') . '.';
        if ($debug_screen) {$this->msg .= '<br>' . htmlentities($debug_event);}
    }
    private static function write_log ($event) {
        /*  Writes to WP_DEBUG_LOG if enabled in wp-config:
        *    define('WP_DEBUG', true);
        *    define('WP_DEBUG_LOG', true);
        *    define( 'WP_DEBUG_DISPLAY', false ); or true if you want php errors on screen.
        */
        if (true === WP_DEBUG && true === WP_DEBUG_LOG) {
            if (is_array($event) || is_object($event)) {
                error_log(print_r($event, true));
            } else {
                error_log($event);
            }
        }
    }
}