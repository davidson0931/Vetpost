<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Vetpost_Api' ) ) :

	/**
	 * Main Vetpost_Api Class.
	 *
	 * @package		VETPOSTAPI
	 * @subpackage	Classes/Vetpost_Api
	 * @since		1.0.0
	 * @author		Jon Doe
	 */
	final class Vetpost_Api {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Vetpost_Api
		 */
		private static $instance;

		/**
		 * VETPOSTAPI helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Vetpost_Api_Helpers
		 */
		public $helpers;

		/**
		 * VETPOSTAPI settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Vetpost_Api_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'vetpost-api' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'vetpost-api' ), '1.0.0' );
		}

		/**
		 * Main Vetpost_Api Instance.
		 *
		 * Insures that only one instance of Vetpost_Api exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Vetpost_Api	The one true Vetpost_Api
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Vetpost_Api ) ) {
				self::$instance					= new Vetpost_Api;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Vetpost_Api_Helpers();
				self::$instance->settings		= new Vetpost_Api_Settings();

				//Fire the plugin logic
				new Vetpost_Api_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'VETPOSTAPI/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once VETPOSTAPI_PLUGIN_DIR . 'core/includes/classes/class-vetpost-api-helpers.php';
			require_once VETPOSTAPI_PLUGIN_DIR . 'core/includes/classes/class-vetpost-api-settings.php';

			require_once VETPOSTAPI_PLUGIN_DIR . 'core/includes/classes/class-vetpost-api-run.php';

			require_once VETPOSTAPI_PLUGIN_DIR . 'core/includes/classes/class-vetpost-cron-run.php';
			
			

		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'vetpost-api', FALSE, dirname( plugin_basename( VETPOSTAPI_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.