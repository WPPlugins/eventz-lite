<?php
/**
 * HTTP Class.
 * @link       http://onebyte.nz
 * This class defines all code necessary to run during the plugin's deactivation.
 * @since      1.0.0
 * @package    Eventz Lite
 * @subpackage Eventz/includes
 * @author     Craig Sugden - onebyte.nz <info@onebyte.nz>
 */
class Eventz_Lite_HTTP {
    private $username;
    private $password;

    public function __construct($user,$pass) {
        $this->username = $user;
        $this->password = $pass;
    }
    public function get_api_data($url) {
        $data = $this->get_http_data($url);
        return $data;
    }
    private function get_http_data ($url) {
        $args = array(
            'timeout' => 10,
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
            )
        );
        $response = wp_safe_remote_get($url, $args);
        if (is_wp_error($response)){
            $data = '<!--eventz-error-->' . htmlentities($response->get_error_message());
        } else {
            $data = wp_remote_retrieve_body($response);
        }
        return $data; 
    }
}

