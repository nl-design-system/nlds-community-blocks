<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin admin area. This file also includes
 * all the dependencies used by the plugin, registers the activation and deactivation functions, and starts the plugin.
 *
 * @since             1.0.0
 * @package           Nlds_Community_Blocks
 *
 * @wordpress-plugin
 * Plugin Name:       NL Design System Community Blocks
 * Plugin URI:        https://nldesignsystem.nl/
 * Description:       Adds NL Design System Community Blocks to the Gutenberg editor.
 * Version:           1.0.0
 * Author:            Acato
 * Author URI:        https://www.acato.nl
 * Text Domain:       nlds-community-blocks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-autoloader.php';
spl_autoload_register( [ '\Nlds_Community_Blocks\Includes\Autoloader', 'autoload' ] );

// Make sure global functions are loaded.
foreach ( glob( plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . '*.php' ) as $ncb_plugin_functions ) {
	require_once $ncb_plugin_functions;
}

// Make sure global filters are loaded.
foreach ( glob( plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'filters' . DIRECTORY_SEPARATOR . '*.php' ) as $ncb_plugin_filter ) {
	require_once $ncb_plugin_filter;
}

if ( ! defined( 'NCB_VERSION' ) ) {
	define( 'NCB_VERSION', '1.0.1' );
}

if ( ! defined( 'NCB_PLUGIN_PATH' ) ) {
	define( 'NCB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Begins execution of the plugin.
 */
new \Nlds_Community_Blocks\Includes\Plugin();
