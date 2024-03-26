<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Includes
 */

namespace Nlds_Community_Blocks\Includes;

use Nlds_Community_Blocks\Admin\Admin;
use Nlds_Community_Blocks\Frontend\Frontend;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Includes
 * @author     Richard Korthuis <richardkorthuis@acato.nl>
 */
class Plugin {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Define the locale, and set the hooks for the admin area and the public-facing side of the site.
	 */
	public function __construct() {
		$this->define_constants();

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_frontend_hooks();
	}

	/**
	 * Define constants used by the plugin.
	 */
	private function define_constants() {
		if ( ! defined( 'NCB_ABSPATH' ) ) {
			define( 'NCB_ABSPATH', plugin_dir_path( __DIR__ ) );
		}
		if ( ! defined( 'NCB_BLOCKS_DIR' ) ) {
			define( 'NCB_BLOCKS_DIR', 'src/blocks/' );
		}
		if ( ! defined( 'NCB_TEMPLATE_DIR' ) ) {
			define( 'NCB_TEMPLATE_DIR', 'src/templates/' );
		}
		if ( ! defined( 'NCB_ASSETS_DIR' ) ) {
			define( 'NCB_ASSETS_DIR', 'build/' );
		}
		if ( ! defined( 'NCB_ASSETS_URL' ) ) {
			define( 'NCB_ASSETS_URL', esc_url( trailingslashit( plugins_url( '', __DIR__ ) ) . NCB_ASSETS_DIR ) );
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook with WordPress.
	 */
	private function set_locale() {
		$plugin_i18n = new I18n();

		add_action( 'plugins_loaded', [ $plugin_i18n, 'load_plugin_textdomain' ] );
	}

	/**
	 * Register all the hooks related to the admin area functionality of the plugin.
	 */
	private function define_admin_hooks() {
		$admin = new Admin();
		$admin->load_blocks();

		add_action( 'enqueue_block_editor_assets', [ $admin, 'enqueue_block_editor_assets' ], 99 );

		if ( is_wp_version_compatible( '5.8' ) ) {
			add_filter( 'allowed_block_types_all', [ $admin, 'allowed_block_types' ], 5, 1 );
		} else {
			// Deprecated since WordPress 5.8, read `https://developer.wordpress.org/reference/hooks/allowed_block_types/`.
			add_filter( 'allowed_block_types', [ $admin, 'allowed_block_types' ], 5, 1 );
		}

		add_action( 'admin_menu', [ $admin, 'nlds_community_blocks_add_settings_page' ] );
		add_action( 'admin_init', [ $admin, 'nlds_community_blocks_register_settings' ] );
		add_filter( 'admin_body_class', [ $admin, 'ncb_editor_body_class_by_community_theme' ] );

		$frontend = new Frontend();
		add_action( 'admin_head', [ $frontend, 'ncb_action_community_icons_based_on_block' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_settings_page_assets' ] );
	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function define_frontend_hooks() {
		$frontend = new Frontend();

		if ( is_wp_version_compatible( '5.8' ) ) {
			add_filter( 'block_categories_all', [ $frontend, 'register_custom_block_category' ], 10, 1 );
		} else {
			add_filter( 'block_categories', [ $frontend, 'register_custom_block_category' ], 10, 1 );
		}

		add_action( 'enqueue_block_assets', [ $frontend, 'enqueue_block_assets' ] );
		add_action( 'enqueue_block_assets', [ $frontend, 'dequeue_block_assets' ] );
		add_filter( 'body_class', [ $frontend, 'ncb_body_class_by_community_theme' ] );
		add_action( 'wp_head', [ $frontend, 'ncb_action_community_icons_based_on_block' ] );

		$ncb_theme = esc_attr( get_option( 'ncb_organisation', '' ) );
		switch ( $ncb_theme ) {
			case 'denhaag':
				add_filter( 'wp_kses_allowed_html', [ $frontend, 'ncb_denhaag_extend_wp_kses_posts' ] );
				break;
		}
	}

	/**
	 * Checks if file exists and if the file is populated, so we don't enqueue empty files.
	 *
	 * @param string $path The absolute path to the file.
	 *
	 * @return bool|mixed
	 */
	public static function has_resource( $path ) {

		static $resources = null;

		if ( isset( $resources[ $path ] ) ) {
			return $resources[ $path ];
		}

		// Check if resource exists and has content.
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$resources[ $path ] = @file_exists( $path ) && 0 < (int) @filesize( $path );

		return $resources[ $path ];
	}
}
