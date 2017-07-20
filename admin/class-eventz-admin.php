<?php
/**
 * The admin-specific functionality of the plugin.
 * @link       http://onebyte.nz
 * @since      1.0.0
 * @package    Eventz Lite
 * @subpackage Eventz/admin
 * @author     Craig Sugden - onebyte.nz <info@onebyte.nz>
 */
class Eventz_Lite_Admin {
    private $plugin_name;
    private $version;
    private $options;
    private $eventfinda_link;
    private $option_name = 'plugin_eventz_lite_options';

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->options = get_option($this->option_name);
        if ($this->options !== false) {
            if ($this->options['_version'] !== $version) {
            $this->options['_version'] = $version;
            update_option($this->option_name, $this->options);
        }
        }
        add_action( 'wp_ajax_check_user', array($this, $this->plugin_name . '_check_user'));
    }
    public function enqueue_styles() {
        /* An instance of this class should be passed to the run() function
         * defined in Eventz_Loader as all of the hooks are defined
         * in that particular class.
         * The Eventz_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/eventz-admin.min.css', array(), $this->version, 'all' );
    }
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/eventz-admin.min.js', '', $this->version, false);
        wp_enqueue_script('jquery-form');
        if(!wp_script_is('jquery-ui-dialog')) {wp_enqueue_script('jquery-ui-dialog');} 
    }
    /*
        * Add settings action link to the plugins page.
        * @since 1.0.0
    */
    public function add_settings_links($links) {
        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=eventz-lite') . '">' . __('Settings', 'eventz-lite') . '</a>',
            '<a href="https://support.onebyte.nz/" target="_blank">' . __('Support', 'eventz-lite') . '</a>',
            '<a href="https://plugin.onebyte.nz/eventz-pro/" target="_blank">' . __('Go Pro', 'eventz-lite') . '</a>'
        );
        return array_merge($settings_link, $links);
    }
    public function add_options_page() {
        $this->plugin_screen_hook_suffix = add_options_page(
            __('Eventz Lite', 'eventz-lite'),
            __('Eventz Lite', 'eventz-lite'),
            'manage_options',
            'eventz-lite',
            array($this, 'display_options_page')
        );
    }
    public function display_options_page() {
        if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient permissions to access this page.'));
        }
        include_once 'partials/eventz-admin-display.php';
    }
    public function register_setting() {
        register_setting(
            'eventz-lite',
            $this->option_name,
            array($this, $this->plugin_name . '_validate_options') 
	);
        add_settings_section(
            '_general',
            __('', 'eventz-lite' ),
            array( $this, $this->plugin_name . '_general_cb' ),
            $this->plugin_name . '_general'
        );
        add_settings_field(
            '_endpoint',
            __('Eventfinda API Endpoint', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter"' . 
            'title="' . __('The Eventfinda server you would like to get the event listings from.', 'eventz-lite' ) . '"></span>',
            array( $this, $this->plugin_name . '_endpoint_cb' ),
            $this->plugin_name . '_general',
            '_general',
            array( 'label_for' => '_endpoint' )
        );
        add_settings_field(
            '_username',
            __('Eventfinda API User Name', 'eventz-lite') . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('Your Eventfinda API username. You can request this from the Eventfinda API page.', 'eventz-lite') .'"></span>',
            array( $this, $this->plugin_name . '_username_cb' ),
            $this->plugin_name . '_general',
            '_general',
            array( 'label_for' => '_username' )
        );
        add_settings_field(
            '_password',
            __('Eventfinda API Password', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('Your Eventfinda API password.', 'eventz-lite') . '"></span>',
            array( $this, $this->plugin_name . '_password_cb' ),
            $this->plugin_name . '_general',
            '_general',
            array( 'label_for' => '_password' )
        );
        add_settings_section(
            '_display',
            __('', 'eventz-lite' ),
            array( $this, $this->plugin_name . '_general_cb' ),
            $this->plugin_name . '_display'
        );
        add_settings_field(
            '_display_options_header',
            __('Listing Display Options', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('Select which items to show for each event listing.', 'eventz-lite') . '"></span>',
            array( $this, $this->plugin_name . '_display_options_header_cb' ),
            $this->plugin_name . '_display',
            '_display',
            array( 'label_for' => '_display_options_header' )
        );
        add_settings_field(
            '_display_options',
            __('', 'eventz-lite' ),
            array( $this, $this->plugin_name . '_display_options_cb' ),
            $this->plugin_name . '_display',
            '_display',
            array( 'label_for' => '_display_options' )
        );
        add_settings_field(
            '_excerpt',
            __('Excerpt Length', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('Select how many characters to show for each event description.', 'eventz-lite') . '"></span>',
            array( $this, $this->plugin_name . '_excerpt_cb' ),
            $this->plugin_name . '_display',
            '_display',
            array( 'label_for' => '_excerpt' )
        );
        add_settings_field(
            '_results_pp',
            __('Results Per Page', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" title="' . 
            '' . __('Select how many listings to show per page.', 'eventz-lite') . '"></span>',
            array( $this, $this->plugin_name . '_results_pp_cb' ),
            $this->plugin_name . '_display',
            '_display',
            array( 'label_for' => '_results_pp' )
        );
        add_settings_section(
            '_misc',
            __('', 'eventz-lite' ),
            array( $this, $this->plugin_name . '_general_cb' ),
            $this->plugin_name . '_misc'
        );
        add_settings_field(
            '_debug',
            __('Enable Debugging', 'eventz' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('Check this box to write errors to the Wordpress Debug Log', 'eventz-lite') .
                '. ' . __('Set WP_DEBUG and WP_DEBUG_LOG to true in wp-config.php', 'eventz-lite') .
                    '. ' . __('WP_DEBUG_DISPLAY can be set to false to hide PHP errors on the page', 'eventz-lite') .
                        '."></span>',
            array( $this, $this->plugin_name . '_debug_cb' ),
            $this->plugin_name . '_misc',
            '_misc',
            array( 'label_for' => '_debug' )
        );
        add_settings_field(
            '_debug_screen',
            __('Onscreen Debugging', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('Check this box to add extended error messages to the public facing pages on the site', 'eventz-lite') .
            '."></span>',
            array( $this, $this->plugin_name . '_debug_screen_cb' ),
            $this->plugin_name . '_misc',
            '_misc',
            array( 'label_for' => '_debug_screen' )
        );
        add_settings_field(
            '_delete_options',
            __('Delete Settings On Uninstall', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('Check this box to delete the plugin settings on uninstall', 'eventz-lite') . '."></span>',
            array( $this, $this->plugin_name . '_delete_options_cb' ),
           $this->plugin_name . '_misc',
            '_misc',
            array( 'label_for' => '_delete_options' )
        );
        add_settings_field(
            '_eventfinda_branding',
            __('Eventfinda Branding', 'eventz-lite' ) . ': <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
            'title="' . __('', 'eventz-lite') . 
                '. ' . __('Eventfinda API terms require that you display a link to their site', 'eventz-lite') . 
                    '. ' . __('You can choose to display the Eventfinda logo or a plain text link', 'eventz-lite') . '."></span>',
            array( $this, $this->plugin_name . '_eventfinda_branding_cb' ),
            $this->plugin_name . '_misc',
            '_misc',
            array( 'label_for' => '_eventfinda_branding' )
        );
        add_settings_field(
            '_plugin_branding',
            __('Plugin Branding', 'eventz-lite' ) . ' <span id="eventz-icon" class="dashicons dashicons-editor-help icenter" ' .
                'title="' . __('If you would like to display a link to our web site that would be very much appreciated', 'eventz-lite') . 
                    '. ' . __('You can choose to display the eventz lite logo or a plain text link', 'eventz-lite') .
                        '."></span>',
            array( $this, $this->plugin_name . '_plugin_branding_cb' ),
            $this->plugin_name . '_misc',
            '_misc',
            array( 'label_for' => '_plugin_branding' )
        );
    }
    public function eventz_lite_validate_options ($input) {
        $default_values = array (
            '_version' => $this->version,
            '_endpoint' => 'api.eventfinda.co.nz',
            '_username' => '',
            '_password' => '',
            '_event_location' => '1',
            '_event_date' => '1',
            '_event_category' => '1',
            '_event_excerpt' => '220',
            '_event_separator' => '1',
            '_results_pp' => '',
            '_debug' => '0',
            '_debug_screen' => '0',
            '_delete_options' => '0',
            '_eventfinda_logo' => '0',
            '_eventfinda_text' => '0',
            '_show_plugin_logo' => '0',
            '_show_plugin_link' => '0'
        );
        if (!is_array($input)) {return $default_values;}
        $values = shortcode_atts($default_values, $input);
        $out = array ();
        foreach ($default_values as $key => $value) {
            switch ($values[$key]) {
                case empty($values[$key]):
                    $out[$key] = $value;
                    break;
                default:
                    $out[$key] = $values[$key];
                    break;
            }
        }
        return $out;
    }
    public function eventz_lite_general_cb() {
        echo '<p>' . __('<small>onebyte Eventz Lite Version ' . $this->options['_version'] . '</small>', 'eventz-lite') . '</p>' .
            '<input type="hidden" name="' . $this->option_name . '[_version]" value="' . $this->version . '">';
    }
    public function eventz_lite_endpoint_cb() {
        $request_link = '';
        $endpoint = $this->options['_endpoint'];
        switch ($endpoint) {
            case 'api.eventfinda.com.au':
                $this->eventfinda_link = 'www.eventfinda.com.au';
                break;
            case 'api.eventfinda.sg':
                $this->eventfinda_link = 'www.eventfinda.sg';
                break;
            case 'api.wohintipp.at':
                $this->eventfinda_link = 'www.wohintipp.at';
                break;
            case 'api.eventfinda.co.nz':
                $this->eventfinda_link = 'www.eventfinda.co.nz';
                break;
            default:
                $this->eventfinda_link = 'www.eventfinda.co.nz';
                break;
        }
        $array = array(
            1=>"api.eventfinda.co.nz",
            2=>"api.eventfinda.sg",
            3=>"api.eventfinda.com.au",
            4=> "api.wohintipp.at"
        );                
        echo '<select name="' . $this->option_name . '[_endpoint]" id="_endpoint">' . "\r\n";
        foreach($array as $key => $value) {
            if ($value === $endpoint) {
                echo "<option selected value='$value'>$value</option>" . "\r\n";
            } else {
                echo "<option value='$value'>$value</option>" . "\r\n";
            }
        }
        $username = $this->options['_username'];
        If (!$username) {
            $request_link = '<a name="_apilink" id="_apilink" href="http://www.eventfinda.co.nz/api/v2/index" target="_blank">' . __('Request Eventfinda API Key', 'eventz-lite') . '</a>';
        }
        echo '</select> ' . $request_link . "\r\n";
    }
    public function eventz_lite_username_cb() {
        $username = $this->options['_username'];
        echo '<input type="text" maxlength="40" data-rule-required="true" data-msg-required=" ' .
            __('Please enter your Eventfinda API user name', 'eventz-lite' ) . '." style="width:300px;" name="' . 
                $this->option_name . '[_username]" id="_username" value="' . $username . '" required>' . 
                    "\r\n";
        if (!$this->options['_endpoint']) {}
    }
    public function eventz_lite_password_cb() {
        $password = $this->options['_password'];
        echo '<input type="password" maxlength="30" data-rule-required="true" data-msg-required=" ' .
            __('Please enter your Eventfinda API password', 'eventz-lite') . '." style="width:300px;" name="' . $this->option_name . 
                '[_password]" id="_password" value="' . $password . '" required>' .
                        "\r\n";
    }
    public function eventz_lite_display_options_header_cb() { 
        echo __('Check the options below to enable or disable', 'eventz-lite') . ': ';
    }
    public function eventz_lite_display_options_cb() {
        $loc_checked = '';
        $date_checked = '';
        $cat_checked = '';
        $sep_checked = '';        
        $show_event_location = intval($this->options['_event_location']);
        $show_event_date = intval($this->options['_event_date']);
        $show_event_category = intval($this->options['_event_category']);
        $show_event_separator = intval($this->options['_event_separator']);
        if ($show_event_location === 1) {$loc_checked = 'checked';}
        if ($show_event_date === 1) {$date_checked = 'checked';}
        if ($show_event_category === 1) {$cat_checked = 'checked';}
        if ($show_event_separator === 1) {$sep_checked = 'checked';}
        
        $str =  '    <fieldset>' . 
                '        <legend class="screen-reader-text"><span>' . __('Listing Display Options', 'eventz-lite') . '</span></legend>' . 
                '        <label for="_event_location">' . 
                '        <input type="hidden" name="' . $this->option_name . '[_event_location]" id="_event_location" value="0">' . "\r\n" .
                '        <input type="checkbox" name="' . $this->option_name . '[_event_location]" id="_event_location" value="1" ' . $loc_checked . '>' . 
                '        ' . __('Event Location / Venue', 'eventz-lite') . '</label>' . 
                '        <br>' . 
                '        <label for="_event_date">' . 
                '        <input type="hidden" name="' . $this->option_name . '[_event_date]" id="_event_date" value="0">' . "\r\n" .
                '        <input type="checkbox" name="' . $this->option_name . '[_event_date]" id="_event_date" value="1" ' . $date_checked . '>' . 
                '        ' . __('Event Start Date', 'eventz-lite') . '</label>' . 
                '        <br>' . 
                '        <label for="_event_category">' . 
                '        <input type="hidden" name="' . $this->option_name . '[_event_category]" id="_event_category" value="0">' . "\r\n" .
                '        <input type="checkbox" name="' . $this->option_name . '[_event_category]" id="_event_category" value="1" ' . $cat_checked . '>' . 
                '        ' . __('Event Category', 'eventz-lite') . '</label>' . 
                '        <br>' . 
                '        <label for="_event_separator">' . 
                '        <input type="hidden" name="' . $this->option_name . '[_event_separator]" id="_event_separator" value="0">' . "\r\n" .
                '        <input type="checkbox" name="' . $this->option_name . '[_event_separator]" id="_event_separator" value="1" ' . $sep_checked . '>' . 
                '        ' . __('Event Separator', 'eventz-lite') . '</label>' . 
                '        <br>' . 
                '    </fieldset>';
        echo $str;
    }
    public function eventz_lite_excerpt_cb() {
        $str_options = '';
        $array = array(
            1=>"10",2=>"20",3=>"30",4=>"40",5=>"50",6=>"60",7=>"70",8=>"80",9=>"90",10=>"100",
            11=>"110",12=>"120",13=>"130",14=>"140",15=>"150",16=>"160",17=>"170",18=>"180",
            19=>"190",20=>"200",21=>"210",22=>"220"
        );
        $excerpt_length = intval($this->options['_event_excerpt']);
        foreach($array as $key => $value) {
            if (intval($value) === $excerpt_length) {
                $str_options .= "<option selected value='$value'>$value</option>" . "\r\n";
            } else {
                $str_options .=  "<option value='$value'>$value</option>" . "\r\n";
            }
        }
        $str =  '    <fieldset>' . 
                '        <select name="' . $this->option_name . '[_event_excerpt]" id="_event_excerpt">' . 
                '       ' . $str_options . 
                '        </select>' .
                '    </fieldset>';
        echo $str;
    }
    public function eventz_lite_results_pp_cb() {
        $results = intval($this->options['_results_pp']);
        $array = array(
            1=>"5",
            2=>"10",
            3=>"15",
            4=>"20"
        );  
        echo '<select name="' . $this->option_name . '[_results_pp]" id="_results_pp">' . "\r\n";
        foreach($array as $key => $value) {
            if (intval($value) === $results) {
                echo "<option selected value='$value'>$value</option>" . "\r\n";
            } else {
                echo "<option value='$value'>$value</option>" . "\r\n";
            }
        }
        echo '</select>' . "\r\n";
    }
    public function eventz_lite_debug_cb() {
        $checked = '';
        $debug = $this->options['_debug'];
        if (intval($debug) === 1) {$checked = 'checked';}
        echo '<input type="hidden" name="' . $this->option_name . '[_debug]" id="_debug" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_debug]" id="_debug" value="1" ' . 
                $checked . '>' . "\r\n";
    }
    public function eventz_lite_debug_screen_cb() {
        $checked = '';
        $debug_screen = $this->options['_debug_screen'];
        if (intval($debug_screen) === 1) {$checked = 'checked';}
        echo '<input type="hidden" name="' . $this->option_name . '[_debug_screen]" id="_debug_screen" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_debug_screen]" id="_debug_screen" value="1" ' . 
                $checked . '>' . "\r\n";
    }
    public function eventz_lite_delete_options_cb() {
        $checked = '';
        $delete_options = $this->options['_delete_options'];
        if (intval($delete_options) === 1) {$checked = 'checked';}
        echo '<input type="hidden" name="' . $this->option_name . '[_delete_options]" id="_delete_options" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_delete_options]" id="_delete_options" value="1" ' . 
                $checked . '>' . "\r\n";
    }
    public function eventz_lite_eventfinda_branding_cb() {
        $logo_checked = '';
        $text_checked = '';
        $eventfinda_show_logo = intval($this->options['_eventfinda_logo']);
        $eventfinda_show_text = intval($this->options['_eventfinda_text']);
        $eventfinda_logo = '<a href="http://' . $this->eventfinda_link . '" title="' . __('Powered by Eventfinda', 'eventz-lite') . '" target="_blank">' . "\r\n" .
            '<img width="180" height="50" border="1" alt="Powered by Eventfinda" src="' . $this->eventz_lite_plugin_dir() . 'img/eventfinda.gif"></a>';
        $eventfinda_text = '<a href="http://' . $this->eventfinda_link . '" title="' . __('Powered by Eventfinda', 'eventz-lite') . '" target="_blank">' .
            __('Powered by Eventfinda', 'eventz-lite') . '</a>';
        if (intval($eventfinda_show_logo) === 1) {$logo_checked = 'checked';}
        if (intval($eventfinda_show_text) === 1) {$text_checked = 'checked';}
        echo '<input type="hidden" name="' . $this->option_name . '[_eventfinda_logo]" id="_eventfinda_logo" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_eventfinda_logo]" id="_eventfinda_logo" value="1" ' . $logo_checked . '>' . "\r\n" . 
            $eventfinda_logo . 
            '<input type="hidden" name="' . $this->option_name . '[_eventfinda_text]" id="_eventfinda_text" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_eventfinda_text]" id="_eventfinda_text" value="1" ' . $text_checked . '>' . "\r\n" . 
                $eventfinda_text . "\r\n";
    }
    public function eventz_lite_plugin_branding_cb() {
        $logo_checked = '';
        $text_checked = '';
        $show_plugin_logo = intval($this->options['_show_plugin_logo']);
        $show_plugin_link = intval($this->options['_show_plugin_link']);
        $plugin_logo = '<a href="http://plugin.onebyte.nz" title="' . __('Get the Plugin', 'eventz-lite') . '" target="_blank">' . "\r\n" .
            '<img width="180" height="50" alt="Eventfinda" src="' . $this->eventz_lite_plugin_dir() . 'img/eventz-lite.png"></a>';
        $plugin_link = '<small><a href="http://plugin.onebyte.nz" title="' . 
            __('Get the Plugin', 'eventz-lite') . '" target="_blank">' . 
                __('Get the Plugin', 'eventz-lite') . '</a></small>';
        if (intval($show_plugin_logo) === 1) {$logo_checked = 'checked';}
        if (intval($show_plugin_link) === 1) {$text_checked = 'checked';}
        echo '<input type="hidden" name="' . $this->option_name . '[_show_plugin_logo]" id="_show_plugin_logo" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_show_plugin_logo]" id="_show_plugin_logo" value="1" ' . $logo_checked . '>' . "\r\n" . 
            $plugin_logo .
            '<input type="hidden" name="' . $this->option_name . '[_show_plugin_link]" id="_show_plugin_link" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_show_plugin_link]" id="_show_plugin_link" value="1" ' . $text_checked . '>' . "\r\n" . 
                $plugin_link . "\r\n";
    }
    public function eventz_lite_show_plugin_link_cb() {
        $checked = '';
        $show_plugin_link = intval($this->options['_show_plugin_link']);
        $plugin_link = '<small><a href="http://plugin.onebyte.nz" title="' . __('Get the Plugin', 'eventz-lite') . '" target="_blank">Get Plugin</a></small>';
        if (intval($show_plugin_link) === 1) {$checked = 'checked';}
        echo '<input type="hidden" name="' . $this->option_name . '[_show_plugin_link]" id="_show_plugin_link" value="0">' . "\r\n" .
            '<input type="checkbox" name="' . $this->option_name . '[_show_plugin_link]" id="_show_plugin_link" value="1" ' . $checked . '>' . "\r\n" . 
                $plugin_link;
    }
    public function eventz_lite_plugin_dir () {
        return plugin_dir_url( __FILE__ );
    }
    /* Ajax Functions */
    public function eventz_lite_check_user () {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $endpoint = $_POST['endpoint'];
        if ($user == '') {return;}
        if ($pass == '') {return;}
        $url = 'http://' . $endpoint . '/v2/events.json?rows=1';
        $args = array(
            'timeout'     => 10,
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($user . ':' . $pass)
            )
        );
        $return = wp_safe_remote_get($url, $args);
        if (is_wp_error($return)){
            echo $return->get_error_message();
        } elseif (strpos(json_encode($return), 'error code') !== false) {
            echo __('Eventfinda API Login Failed', 'eventz-lite') . ': ' . 
                __('Please check your details and try again', 'eventz-lite') . '.<br/><br/>' . 
                    __('Eventfinda API says', 'eventz-lite') . ': ' . $return['response']['code'] . 
                        ': ' . $return['response']['message'];
        } else {
            echo 'true';
        }
        wp_die();
    }
}
