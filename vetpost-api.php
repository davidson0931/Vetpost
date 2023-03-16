<?php
/**
 * Vetpost Api LIVE
 *
 * @package       VETPOSTAPI LIVE
 * @author        Jon Doe
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Vetpost Api LIVE
 * Plugin URI:    https://mydomain.com
 * Description:   Vetpost WooCommerce Plugin/Extension Integration
 * Text Domain:   vetpost-api	
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'VETPOSTAPI_NAME',			'Vetpost Api' );

// Plugin version
define( 'VETPOSTAPI_VERSION',		'1.0.0' );

// Plugin Root File
define( 'VETPOSTAPI_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'VETPOSTAPI_PLUGIN_BASE',	plugin_basename( VETPOSTAPI_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'VETPOSTAPI_PLUGIN_DIR',	plugin_dir_path( VETPOSTAPI_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'VETPOSTAPI_PLUGIN_URL',	plugin_dir_url( VETPOSTAPI_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once VETPOSTAPI_PLUGIN_DIR . 'core/class-vetpost-api.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Jon Doe
 * @since   1.0.0
 * @return  object|Vetpost_Api
 */
function VETPOSTAPI() {
	return Vetpost_Api::instance();
}

VETPOSTAPI();

add_action( 'rest_api_init' , 'register_api_count_item_perstatus' );
		

function register_api_count_item_perstatus()
{
	register_rest_route('order/counts_per/', 'status', [
			'methods'  => WP_REST_SERVER::READABLE,
			'callback' => 'counts_per_status'
		]);
		
}
function counts_per_status(WP_REST_Request $request){
	
	// $results = array("wowo");
	$count_pages = wp_count_posts(  'shop_order' );
	return $count_pages;
}