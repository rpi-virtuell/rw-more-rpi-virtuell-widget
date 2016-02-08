<?php
ini_set("display_errors", 'on');
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	ini_set('error_reporting', E_ALL);
/*
Plugin Name: RW Mehr rpi-virtuell Widget
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Stellt ein Widget zur Verfügung, dass zu anderen Diensten von rpi-virtuell verlinkt
Version: 0.1
Author: Joachim Happel
Author URI: http://joachim-happel.de
License: A "Slug" license name e.g. GPL2
*/

/**
 * More Rpi Virtuell Widget
 *
 * @package   RW Mehr rpi-virtuell Widget
 * @author    Joachim Happel
 * @license   GPL-2.0+
 * @link      https://github.com/rpi-virtuell/rw-rw-more-rpi-virtuell-widget
 */

/*
 * Plugin Name:       Mehr Rpi Virtuell Widget
 * Plugin URI:        https://github.com/rpi-virtuell/rw-more-rpi-virtuell-widget
 * Description:       Stellt ein Widget zur Verfügung, dass zu anderen Diensten von rpi-virtuell verlinkt
 * Version:           0.1
 * Author:            Joachim Happel
 * Author URI:        http://joachim-happel.de
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:       /languages
 * Text Domain:       rw-rw-more-rpi-virtuell-widget
 * Network:           false
 * GitHub Plugin URI: https://github.com/rpi-virtuell/rw-more-rpi-virtuell-widget
 * GitHub Branch:     master
 * Requires WP:       4.0
 * Requires PHP:      5.3
 */


class RW_More_Rpi_Virtuell_Widget {
    /**
     * Plugin version
     *
     * @var     string
     * @since   0.0.1
     * @access  public
     */
    static public $version = "0.0.1";

    /**
     * Singleton object holder
     *
     * @var     mixed
     * @since   0.0.1
     * @access  private
     */
    static private $instance = NULL;

    /**
     * @var     mixed
     * @since   0.0.1
     * @access  public
     */
    static public $plugin_name = NULL;

    /**
     * @var     mixed
     * @since   0.0.1
     * @access  public
     */
    static public $textdomain = NULL;

    /**
     * @var     mixed
     * @since   0.0.1
     * @access  public
     */
    static public $plugin_base_name = NULL;

    /**
     * @var     mixed
     * @since   0.0.1
     * @access  public
     */
    static public $plugin_url = NULL;

    /**
     * @var     string
     * @since   0.0.1
     * @access  public
     */ 
	static public $plugin_path = NULL;

    /**
     * @var     string
     * @since   0.0.1
     * @access  public
     */
    static public $plugin_filename = __FILE__;

    /**
     * @var     string
     * @since   0.0.1
     * @access  public
     */
    static public $plugin_version = '';

    /**
     * Plugin constructor.
     *
     * @since   0.0.1
     * @access  public
     * @uses    plugin_basename
     * @action  @TODO rw_more_rpi_virtuell_widget_init
     */
    public function __construct () {
        // set the textdomain variable
        self::$textdomain = self::get_textdomain();

        // The Plugins Name
        self::$plugin_name = $this->get_plugin_header( 'Name' );

        // The Plugins Basename
        self::$plugin_base_name = plugin_basename( __FILE__ );

        // The Plugins Version
        self::$plugin_version = $this->get_plugin_header( 'Version' );

        // The Url to the Plugins Root
        self::$plugin_url =  plugins_url('',__FILE__ );
        self::$plugin_path = plugin_dir_path(__FILE__ );

        // Load the textdomain
        $this->load_plugin_textdomain();

        // Add Filter & Actions

        add_action('widgets_init',                      array( 'RW_More_Rpi_Virtuell_Widget_Core','init' ) );

        do_action( 'rw_more_rpi_virtuell_widget_init' );
    }

    /**
     * Creates an Instance of this Class
     *
     * @since   0.0.1
     * @access  public
     * @return  Object
     */
    public static function get_instance() {

        if ( NULL === self::$instance )
            self::$instance = new self;

        return self::$instance;
    }

    /**
     * Load the localization
     *
     * @since	0.0.1
     * @access	public
     * @uses	load_plugin_textdomain, plugin_basename
     * @filters rw_more_rpi_virtuell_widget_domain path to translations files
     * @return	void
     */
    public function load_plugin_textdomain() {
       load_plugin_textdomain( self::get_textdomain(), false, apply_filters ( 'rw_more_rpi_virtuell_widget_domain', dirname( plugin_basename( __FILE__ )) .  self::get_textdomain_path() ) );
    }

    /**
     * Get a value of the plugin header
     *
     * @since   0.0.1
     * @access	protected
     * @param	string $value
     * @uses	get_plugin_data, ABSPATH
     * @return	string The plugin header value
     */
    protected function get_plugin_header( $value = 'TextDomain' ) {

        if ( ! function_exists( 'get_plugin_data' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php');
        }

        $plugin_data = get_plugin_data( __FILE__ );
        $plugin_value = $plugin_data[ $value ];

        return $plugin_value;
    }

    /**
     * get the textdomain
     *
     * @since   0.0.1
     * @static
     * @access	public
     * @return	string textdomain
     */
    public static function get_textdomain() {
        if( is_null( self::$textdomain ) )
            self::$textdomain = self::get_plugin_data( 'TextDomain' );

        return self::$textdomain;
    }

    /**
     * get the textdomain path
     *
     * @since   0.0.1
     * @static
     * @access	public
     * @return	string Domain Path
     */
    public static function get_textdomain_path() {
        return self::get_plugin_data( 'DomainPath' );
    }

    /**
     * return plugin comment data
     *
     * @since   0.0.1
     * @uses    get_plugin_data
     * @access  public
     * @param   $value string, default = 'Version'
     *		Name, PluginURI, Version, Description, Author, AuthorURI, TextDomain, DomainPath, Network, Title
     * @return  string
     */
    public static function get_plugin_data( $value = 'Version' ) {

        if ( ! function_exists( 'get_plugin_data' ) )
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        $plugin_data  = get_plugin_data ( __FILE__ );
        $plugin_value = $plugin_data[ $value ];

        return $plugin_value;
    }

}


if ( class_exists( 'RW_More_Rpi_Virtuell_Widget' ) ) {


    add_action( 'plugins_loaded', array( 'RW_More_Rpi_Virtuell_Widget', 'get_instance' ) );

    require_once 'inc/RW_More_Rpi_Virtuell_Widget_Autoloader.php';
    RW_More_Rpi_Virtuell_Widget_Autoloader::register();

    register_activation_hook( __FILE__, array( 'RW_More_Rpi_Virtuell_Widget_Installation', 'on_activate' ) );
    register_uninstall_hook(  __FILE__,	array( 'RW_More_Rpi_Virtuell_Widget_Installation', 'on_uninstall' ) );
    register_deactivation_hook( __FILE__, array( 'RW_More_Rpi_Virtuell_Widget_Installation', 'on_deactivation' ) );
}
