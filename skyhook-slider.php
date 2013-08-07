<?php
/**
 * Skyhook Slider
 *
 * Basic slider plugin based on the FlexSlider jQuery plugin from WooThemes.
 *
 * @package   Skyhook Slider
 * @author    Cory Crowley <cory@skyhookmarketing.com>
 * @license   GPL-2.0+
 * @link      http://skyhookinternetmarketing.com/
 * @copyright 2013 Cory Crowley, Skyhook Internet Marketing
 *
 * Plugin Name: Skyhook Slider
 * Plugin URI:  http://skyhookinternetmarketing.com/
 * Description: Basic slider plugin based on FlexSlider jQuery plugin from WooThemes.
 * Version:     1.0.1
 * Author:      Cory Crowley, Skyhook Internet Marketing
 * Author URI:  http://skyhookinternetmarketing.com/our-team/cory-crowley/
 * Text Domain: sslider
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

/**
 * Skyhook Slider Class
 * 
 * @author Cory Crowley <cory@skyhookmarketing.com>
 * @since  1.0.0
 */
class Skyhook_Slider {

	/**
	 * Class Variables
	 * @since 1.0.0
	 */
	public static $instance;
	private $options;

	/**
	 * Class Constants
	 * @since 1.0.0
	 */
	const VERSION         = '1.0.1';
	const TEXT_DOMAIN     = 'sslider';
	const SCRIPT_PREFIX   = 'sslider';
	const SLIDE_POST_TYPE = 'slide';
	const ACF_LITE        = TRUE;

	/**
	 * Class Constructor
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct() {

		/* Set instance */
		self::$instance = $this;

		/* Initialize ACF */
		$this->init_acf();

		/* Initialize plugin */
		add_action( 'init', array( $this, 'init' ), 9 );
	}

	/**
	 * Initialize ACF
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function init_acf() {

		/* If ACF plugin exists, exit */
		if ( class_exists( 'Acf' ) ) {
			return;
		}

		/* Define ACF Lite */
		define( 'ACF_LITE', self::ACF_LITE );

		/* Include ACF */
		include_once( dirname( __FILE__ ) . '/includes/advanced-custom-fields/acf.php' );
	}

	/**
	 * Init Plugin
	 * 
	 * @access public
	 * @since 1.0.0
	 */
	public function init() {

		/* Translations */
		load_plugin_textdomain( self::TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/lang' );

		/* Register Post Types */
		$this->register_slide_post_type();

		/* Set Slide Icon */
		add_action( 'admin_head', array( $this, 'set_slide_menu_icons' ) );

		/* Register and Add Scripts and Styles */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );

		/* Register Shortcodes */
		$this->register_shortcodes();

		/* Regiser ACF Fields */
		$this->register_acf_fields();
	}

	/**
	 * Slide Post Type
	 *
	 * @access public
	 * @uses   register_post_type( $post_type, $args )
	 * @since  1.0.0
	 */
	public function register_slide_post_type() {
		
		/**
		 * Post Type Labels
		 * @var 	array $labels
		 * @since 1.0.0
		 */
		$labels = array (
			'name'               => __( 'Slides',                        self::TEXT_DOMAIN ),
			'singular_name'      => __( 'Slide',                         self::TEXT_DOMAIN ),
			'add_new_item'       => __( 'Add New Slide',                 self::TEXT_DOMAIN ),
			'edit_item'          => __( 'Edit Slide',                    self::TEXT_DOMAIN ),
			'new_item'           => __( 'New Slide',                     self::TEXT_DOMAIN ),
			'view_item'          => __( 'View Slide',                    self::TEXT_DOMAIN ),
			'search_items'       => __( 'Search Slides',                 self::TEXT_DOMAIN ),
			'not_found'          => __( 'No Slides Found',               self::TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No Slides found in Trash',      self::TEXT_DOMAIN ),
			'parent'             => __( 'Parent Slide',                  self::TEXT_DOMAIN ),
			'add_new'            => _x( 'Add New', 'i.e. Add new Slide', self::TEXT_DOMAIN ),
			'edit'               => _x( 'Edit',    'i.e. Edit Slide',    self::TEXT_DOMAIN ),
			'view'               => _x( 'View',    'i.e. View Slide',    self::TEXT_DOMAIN ),
		);
	
		/**
		 * Post Type Arguments
		 * @var 	array $args
		 * @since 1.0.0
		 */
		$args = array (
			'label'               => _x( 'Slides', 'post type label', self::TEXT_DOMAIN ),
			'labels'              => $labels,
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 6,
			'hierarchical'        => true,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'can_export'          => false,
		);

		/**
		 * Register Post Type
		 * @since 1.0.0
		 */
		register_post_type( self::SLIDE_POST_TYPE, $args );
	}

	/**
	 * Set Menu Icons
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function set_slide_menu_icons() {
		echo '<style type="text/css">
						@font-face {
						    font-family: "Genericons";
						    src: url(data:application/x-font-woff;charset=utf-8;base64,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) format("woff"),
						         url("'.plugins_url( 'fonts/genericons-regular-webfont.ttf', __FILE__ ).'") format("truetype"),
						         url("'.plugins_url( 'fonts/genericons-regular-webfont.svg', __FILE__ ).'#genericonsregular") format("svg");
						    font-weight: normal;
						    font-style: normal;
						}
          	#adminmenu #menu-posts-slide div.wp-menu-image{
          		background:none;
          	}
          	#adminmenu #menu-posts-slide div.wp-menu-image:before {
          		content: "\f422";
          		color:#999;
			        display: inline-block;
			        -webkit-font-smoothing: antialiased;
			        font: normal 24px/1 "Genericons";
			        vertical-align: middle;
			        padding:2px 0 0 2px;
          	}
						#adminmenu #menu-posts-slide:hover div.wp-menu-image:before { color:#000; }
						#adminmenu #menu-posts-slide.wp-has-current-submenu div.wp-menu-image:before { color:#FFF; }
						.icon32-posts-slide { background-image:none !important; }
						.icon32-posts-slide:before {
							content: "\f422";
          		color:#999;
			        display: inline-block;
			        -webkit-font-smoothing: antialiased;
			        font: normal 38px/1 "Genericons";
			        vertical-align: middle;
						}
        	</style>';
	}

	/**
	 * Register Shortcodes
	 * 
	 * @access public
	 * @since 1.0.0
	 */
	public function register_shortcodes() {
		include_once( 'includes/shortcodes.php' );
	}

	/**
	 * Register ACF Fields
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function register_acf_fields() {
		include_once( 'includes/acf-fields.php' );
	}

	/**
	 * Enqueue Scripts and styles.
	 * 
	 * @access public
	 * @since  1.0.0
	 */
	public function enqueue_scripts_styles() {

		/* Make sure not admin */
		if ( is_admin() ) {
			return;
		}
		
		/* Prefix */
		$prefix = self::SCRIPT_PREFIX;

		/* Default CSS and JS file */
		wp_enqueue_style( "{$prefix}-css", plugins_url( "assets/css/{$prefix}.css", __FILE__ ), array(), self::VERSION );
		wp_enqueue_script( "{$prefix}-js", plugins_url( "assets/js/{$prefix}.js", __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * Debug Function
	 *
	 * @access private
	 * @param  string $message
	 * @since  1.0.0
	 */
	private function debug( $message ) {
	  if ( WP_DEBUG === true ) {
	    if ( is_array( $message ) || is_object( $message ) ) {
				error_log( print_r( $message, true ) );
	    } else {
				error_log( $message );
	    }
	  }
	}
}
new Skyhook_Slider;