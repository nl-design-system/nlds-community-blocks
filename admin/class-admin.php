<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Admin
 * @since      1.0.0
 */

namespace Nlds_Community_Blocks\Admin;

use Nlds_Community_Blocks\Includes\Plugin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the admin-specific functionality of the plugin.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Admin
 * @author     Richard Korthuis <richardkorthuis@acato.nl>
 */
class Admin {

	/**
	 * Settings page variables.
	 *
	 * @var string[]
	 */
	private $settings_page = [
		'slug'       => 'nlds-community-blocks-settings',
		'page_title' => 'NLDS Community Blocks Settings',
		'menu_title' => 'NLDS Community Blocks',
	];

	/**
	 * Enqueue assets for dynamic blocks for the block editor.
	 */
	public function enqueue_block_editor_assets() {
		$script_asset_path = NCB_ABSPATH . NCB_ASSETS_DIR . 'index.asset.php';
		if ( Plugin::has_resource( $script_asset_path ) ) {
			$script_asset = require $script_asset_path;
		} else {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( 'nlds-community-blocks (index.asset.php) isn`t found. Forgot to run `pnpm run build`?' );

			return false;
		}

		if ( Plugin::has_resource( NCB_ABSPATH . NCB_ASSETS_DIR . 'index.js' ) ) {
			wp_enqueue_script(
				'nlds-community-blocks-editor',
				esc_url( NCB_ASSETS_URL ) . 'index.js',
				$script_asset['dependencies'],
				$script_asset['version'],
				false
			);

			wp_localize_script( 'nlds-community-blocks-editor', 'ncb_editor_variables', apply_filters( 'ncb_editor_variables', [], get_the_ID() ) );

			wp_set_script_translations(
				'nlds-community-blocks-editor',
				'nlds-community-blocks',
				trailingslashit( NCB_ABSPATH ) . 'languages/'
			);
		}

		// Loop through all blocks and check for existing `style.css` and enqueue it.
		foreach ( glob( NCB_ABSPATH . NCB_ASSETS_DIR . 'blocks/*/*', GLOB_ONLYDIR ) as $ncb_community ) {

			if ( ! Plugin::has_resource( $ncb_community . '/editor.css' ) ) {
				// Continue if the file is empty.
				continue;
			}

			$ncb_parts  = explode( '/', $ncb_community );
			$ncb_handle = implode( '-', array_slice( $ncb_parts, - 2 ) );

			wp_enqueue_style(
				"ncb-$ncb_handle-block-editor",
				ncb_mix( 'blocks/' . implode( '/', array_slice( $ncb_parts, - 2 ) ) . '/editor.css' ),
				[],
				filemtime( $ncb_community . '/editor.css' ) ?? NCB_VERSION
			);
		}
	}

	/**
	 * Load all blocks.
	 */
	public function load_blocks() {
		foreach ( glob( NCB_ABSPATH . NCB_BLOCKS_DIR . '*/*/class-*.php' ) as $file ) {
			include_once $file;
		}

		foreach ( glob( NCB_ABSPATH . NCB_BLOCKS_DIR . '*/*/block.php' ) as $file ) {
			include_once $file;
		}
	}

	/**
	 * Register all blocks from this plugin as allowed block types.
	 *
	 * @param bool|array $allowed_block_types A list of allowed block types, true if all blocks are allowed.
	 *
	 * @return array $allowed_block_types A list of all blocks registered by this plugin.
	 */
	public function allowed_block_types( $allowed_block_types ) {
		$ncb_block_list = include_once NCB_ABSPATH . NCB_BLOCKS_DIR . 'block-list.php';

		if ( is_array( $allowed_block_types ) && is_array( $ncb_block_list ) ) {
			$allowed_block_types = array_merge( $allowed_block_types, $ncb_block_list );
		} else if( ! is_array( $allowed_block_types ) && is_array( $ncb_block_list ) ) {
			$allowed_block_types = $ncb_block_list;
		}

		return $allowed_block_types;
	}

	/**
	 * Register options page for this plugin.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_add_settings_page() {
		add_options_page(
			$this->settings_page['page_title'],
			$this->settings_page['menu_title'],
			'manage_options',
			$this->settings_page['slug'],
			[ $this, 'nlds_community_blocks_render_settings_page' ]
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_render_settings_page() {
		// Generate a nonce field.
		wp_nonce_field( $this->settings_page['slug'], 'nlds-community-blocks-settings-nonce' );

		?>
		<div class="wrap">
			<?php printf( '<h1>%s</h1>', esc_html( get_admin_page_title() ) ); ?>
			<form method="post" action="options.php">
				<?php
				// Output the settings fields.
				settings_fields( $this->settings_page['slug'] );
				do_settings_sections( $this->settings_page['slug'] );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register the settings fields.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_register_settings() {
		// Register a section.
		add_settings_section(
			$this->settings_page['slug'] . '-section',
			'General Settings',
			'__return_false', // Callback function (we don't need one).
			$this->settings_page['slug']
		);

		// Array of settings to reduce duplication.
		$ncb_settings = [
			[
				'label'       => esc_attr_x( 'Load tokens from:', 'Settings page warning', 'nlds-community-blocks' ),
				'option_name' => 'ncb_municipality',
				'callback'    => 'nlds_community_blocks_render_token_select',
				'sanitize'    => 'sanitize_text_field',
			],
			[
				'label'       => esc_attr_x( 'Disable default WordPress Block style:', 'Settings page warning', 'nlds-community-blocks' ),
				'option_name' => 'ncb_dequeue_default_block_styles',
				'callback'    => 'nlds_community_blocks_render_disable_default_blocks',
				'type'        => 'array',
			],
		];

		foreach ( $ncb_settings as $ncb_setting ) {
			// Register a field.
			add_settings_field(
				$ncb_setting['option_name'],
				$ncb_setting['label'],
				[ $this, $ncb_setting['callback'] ],
				$this->settings_page['slug'],
				$this->settings_page['slug'] . '-section'
			);

			// Register the setting.
			register_setting(
				$this->settings_page['slug'],
				$ncb_setting['option_name'],
				[
					'type'     => ! empty( $ncb_setting['type'] ) ? esc_attr( $ncb_setting['type'] ) : 'string',
					'sanitize' => ! empty( $ncb_setting['sanitize'] ) ? esc_attr( $ncb_setting['sanitize'] ) : '__return_false',
				]
			);
		}
	}

	/**
	 * Render the radio field group to select the Municipality to load the tokens from.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_render_token_select() {
		// Get the current value of the setting.
		$ncb_selected_municipality = get_option( 'ncb_municipality', 'denhaag' );

		$nlds_community_blocks_municipalities = [];

		foreach ( glob( NCB_ABSPATH . NCB_ASSETS_DIR . 'blocks/*', GLOB_ONLYDIR ) as $ncb_community_path ) {
			if ( empty( $ncb_community_path ) ) {
				continue;
			}

			$ncb_exploded_community_path = explode( '/', $ncb_community_path );
			$ncb_community               = end( $ncb_exploded_community_path );

			if ( 'nlds' === $ncb_community ) {
				continue;
			}

			$nlds_community_blocks_municipalities[ $ncb_community ] = ucfirst( $ncb_community );
		}

		// Add a none option.
		$nlds_community_blocks_municipalities['none'] = esc_html__( 'None', 'nlds-community-blocks' );

		// Output the field.
		foreach ( $nlds_community_blocks_municipalities as $value => $label ) {
			echo '<label><input type="radio" name="ncb_municipality" value="' . esc_attr( $value ) . '" ' . checked( $value, $ncb_selected_municipality, false ) . '> ' . esc_html( $label ) . '</label><br/>';
		}
		printf( '<p class="description">%s</p>', esc_attr_x( 'Select the municipality whose tokens you want to load.', 'Settings page description', 'nlds-community-blocks' ) );
	}

	/**
	 * Render the input checkbox group for the WP block styles.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_render_disable_default_blocks() {
		$ncb_default_block_styles = [
			'wp-block-post-content',
			'wp-block-comments',
			'wp-block-navigation',
			'wp-block-site-title',
			'wp-block-page-list',
			'wp-block-group',
			'wp-block-post-featured-image',
			'wp-block-library',
		];

		$ncb_selected_default_block_styles = get_option( 'ncb_dequeue_default_block_styles', [] );

		foreach ( $ncb_default_block_styles as $ncb_default_block_style ) {
			if ( ! empty( $ncb_selected_default_block_styles ) && in_array( $ncb_default_block_style, $ncb_selected_default_block_styles, true ) ) {
				printf( '<label for="ncb_dequeue_default_block_styles--%1$s"><input type="checkbox" id="ncb_dequeue_default_block_styles--%1$s" name="ncb_dequeue_default_block_styles[]" value="%1$s" checked>%1$s</label><br/>', esc_attr( $ncb_default_block_style ) );
			} else {
				printf( '<label for="ncb_dequeue_default_block_styles--%1$s"><input type="checkbox" id="ncb_dequeue_default_block_styles--%1$s" name="ncb_dequeue_default_block_styles[]" value="%1$s">%1$s</label><br/>', esc_attr( $ncb_default_block_style ) );
			}
		}

		printf( '<p class="description">%s</p>', esc_attr_x( 'Some default WordPress Block styles can overwrite the NLDS block styles.', 'Settings page description', 'nlds-community-blocks' ) );
	}

	/**
	 * Extend body classes of the editor.
	 *
	 * @param {string} $classes String of classes.
	 *
	 * @return mixed|string
	 */
	public function ncb_editor_body_class_by_community_theme( $classes ) {
		if ( ! get_current_screen()->is_block_editor ) {
			return $classes;
		}

		$ncb_exploded_classes = explode( ' ', $classes );
		$ncb_theme            = esc_attr( get_option( 'ncb_municipality', 'denhaag' ) );
		if ( ! empty( $ncb_theme ) ) {
			$ncb_exploded_classes[] = "$ncb_theme-theme";
		}

		return implode( ' ', array_filter( $ncb_exploded_classes ) );
	}
}
