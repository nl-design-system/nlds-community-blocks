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
	private array $settings_page = [
		'slug'       => 'nlds-community-blocks-settings',
		'page_title' => 'NLDS Community Blocks Settings',
		'menu_title' => 'NLDS Community Blocks',
	];

	/**
	 * Return strring array of selected blocks.
	 *
	 * @var string[] $selected_blocks string[] of selected blocks.
	 */
	private array $selected_blocks = [];

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
	 * Enqueue settings page assets.
	 *
	 * @return void
	 */
	public function enqueue_settings_page_assets(): void {
		global $pagenow;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Just checking on which admin page we are.
		if ( 'options-general.php' !== $pagenow || ! isset( $_GET['page'] ) || $_GET['page'] !== $this->settings_page['slug'] ) {
			return;
		}

		if ( ! Plugin::has_resource( NCB_ABSPATH . NCB_ASSETS_DIR . 'admin/settings-page.css' ) ) {
			return;
		}

		wp_enqueue_style(
			$this->settings_page['slug'],
			esc_url( NCB_ASSETS_URL ) . 'admin/settings-page.css',
			[],
			filemtime( NCB_ABSPATH . NCB_ASSETS_DIR . 'admin/settings-page.css' ) ?? NCB_VERSION
		);
	}

	/**
	 * Load all blocks.
	 *
	 * @return void
	 */
	public function load_blocks(): void {
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
	 * @source https://developer.wordpress.org/reference/hooks/allowed_block_types_all/
	 *
	 * @return bool|array $allowed_block_types A list of all blocks registered by this plugin.
	 */
	public function allowed_block_types( mixed $allowed_block_types ): mixed {
		$ncb_block_list = get_option( 'ncb_allowed_blocks', [] );
		if ( is_array( $allowed_block_types ) && is_array( $ncb_block_list ) ) {
			// Not sure to override or merge, since the person who implements it, may have different blocks.
			$allowed_block_types = array_merge( $allowed_block_types, $ncb_block_list );
		} elseif ( ! is_array( $allowed_block_types ) && is_array( $ncb_block_list ) ) {
			$allowed_block_types = $ncb_block_list;
		}

		return $allowed_block_types;
	}

	/**
	 * Register options page for this plugin.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_add_settings_page(): void {
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
	public function nlds_community_blocks_render_settings_page(): void {
		// Generate a nonce field.
		wp_nonce_field( $this->settings_page['slug'], $this->settings_page['slug'] . '-nonce' );

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
	public function nlds_community_blocks_register_settings(): void {
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
				'label'       => esc_attr_x( 'Load tokens from:', 'Settings page label', 'nlds-community-blocks' ),
				'option_name' => 'ncb_organisation',
				'callback'    => 'nlds_community_blocks_render_token_select',
				'sanitize'    => 'sanitize_text_field',
			],
			[
				'label'       => esc_attr_x( 'Allowed Blocks', 'Settings page label', 'nlds-community-blocks' ),
				'option_name' => 'ncb_allowed_blocks',
				'callback'    => 'nlds_community_blocks_render_allowed_blocks',
				'type'        => 'array',
			],
			[
				'label'       => esc_attr_x( 'Disable default WordPress Block style:', 'Settings page label', 'nlds-community-blocks' ),
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
	public function nlds_community_blocks_render_token_select(): void {
		// Get the current value of the setting.
		$ncb_selected_municipality = get_option( 'ncb_organisation', '' );

		$nlds_community_blocks_municipalities = [
			'' => esc_attr_x( 'Load my own tokens', 'Settings page municipality option', 'nlds-community-blocks' ),
		];

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

		// Output the field.
		foreach ( $nlds_community_blocks_municipalities as $value => $label ) {
			echo '<label><input type="radio" name="ncb_organisation" value="' . esc_attr( $value ) . '" ' . checked( $value, $ncb_selected_municipality, false ) . '> ' . esc_html( $label ) . '</label><br>';
		}
		printf( '<p class="description">%s</p>', esc_attr_x( 'Select the organisation whose tokens you want to load.', 'Settings page description', 'nlds-community-blocks' ) );
	}

	/**
	 * Render the input checkbox group for the WP block styles.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_render_disable_default_blocks(): void {
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
				printf( '<label for="ncb_dequeue_default_block_styles--%1$s"><input type="checkbox" id="ncb_dequeue_default_block_styles--%1$s" name="ncb_dequeue_default_block_styles[]" value="%1$s" checked>%1$s</label><br>', esc_attr( $ncb_default_block_style ) );
			} else {
				printf( '<label for="ncb_dequeue_default_block_styles--%1$s"><input type="checkbox" id="ncb_dequeue_default_block_styles--%1$s" name="ncb_dequeue_default_block_styles[]" value="%1$s">%1$s</label><br>', esc_attr( $ncb_default_block_style ) );
			}
		}

		printf( '<p class="description">%s</p>', esc_attr_x( 'Some default WordPress Block styles can overwrite the NLDS block styles.', 'Settings page description', 'nlds-community-blocks' ) );
	}

	/**
	 * Render the checkbox groups for the allowed blocks.
	 *
	 * @return void
	 */
	public function nlds_community_blocks_render_allowed_blocks(): void {
		static $registered_blocks = null;
		if ( empty( $registered_blocks ) ) {
			$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
		}

		// Map core blocks in a more readable format which is far more lightweight.
		$ncb_core_blocks = array_reduce(
			$registered_blocks,
			function ( $carry, $block ) {
				if ( ! empty( $block->title ) && str_contains( $block->name, 'core/' ) ) {
					$carry[ $block->category ][ $block->name ] = [
						'title'       => $block->title ?? '',
						'description' => $block->description ?? '',
					];
				}

				return $carry;
			},
			[]
		);

		$this->selected_blocks = (array) get_option( 'ncb_allowed_blocks', [] );

		// Sort the outer array by keys.
		ksort( $ncb_core_blocks );

		foreach ( $ncb_core_blocks as &$core_block_groups ) {
			uasort(
				$core_block_groups,
				function ( $a, $b ) {
					return strcmp( $a['title'], $b['title'] );
				}
			);
		}

		// Render the fieldsets.
		self::fieldsets_render( $ncb_core_blocks );
		self::fieldsets_render( self::get_custom_blocks_meta() );
	}

	/**
	 * Returns multiple fieldsets for the allowed blocks.
	 *
	 * @param array $block_groups Array of grouped blocks by category, mapped to a specific format.
	 *
	 * @return void
	 */
	private function fieldsets_render( array $block_groups ): void {
		foreach ( $block_groups as $ncb_category => $ncb_blocks ) {
			if ( empty( $ncb_blocks ) ) {
				continue;
			}

			/* translators: %1$s: The slug for the className, %2$s the category title. */
			printf(
				'<fieldset class="%1$s__fieldset"><legend class="%1$s__legend">%2$s</legend><ul class="%1$s__list">',
				esc_attr( $this->settings_page['slug'] ),
				esc_html( self::get_block_category_label( $ncb_category ) )
			);

			foreach ( $ncb_blocks as $ncb_block_name => $ncb_block ) :
				/* translators:%1$s the slug for the className, %2$s the block name, %3$s the block title, %4$s the block description, %5$s the checked attribute */
				printf(
					'<li class="%1$s__list-item">
								<input class="%1$s__input" type="checkbox" id="%2$s" name="ncb_allowed_blocks[]" value="%3$s" %5$s>
						        <label for="%2$s" class="%1$s__label">%4$s<br><small>%6$s</small></label>
						   </li>',
					esc_attr( $this->settings_page['slug'] ),
					'ncb_allowed_blocks--' . esc_attr( sanitize_title( $ncb_block_name ) ),
					esc_attr( $ncb_block_name ),
					esc_attr( $ncb_block['title'] ),
					! empty( $this->selected_blocks ) && in_array( $ncb_block_name, $this->selected_blocks, true ) ? 'checked' : '',
					esc_attr( $ncb_block['description'] ),
				);
			endforeach;

			echo '</ul></fieldset>';
		}
	}

	/**
	 * Get the custom blocks meta from the blocks that are compiled.
	 *
	 * @return array
	 */
	private static function get_custom_blocks_meta(): array {
		$ncb_blocks_to_return = [];
		foreach ( glob( NCB_ABSPATH . NCB_ASSETS_DIR . 'blocks/*/*/block.json' ) as $ncb_block_meta_file ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- We need to read the file, which is from this plugin.
			$block = json_decode( file_get_contents( $ncb_block_meta_file ), false );
			$ncb_blocks_to_return[ $block->category ][ $block->name ] = [
				'title'       => $block->title ?? '',
				'description' => $block->description ?? '',
			];
		}

		return $ncb_blocks_to_return;
	}

	/**
	 * Returns the label of the custom or core category.
	 *
	 * @param string $slug The category slug.
	 *
	 * @return string
	 */
	private static function get_block_category_label( string $slug ): string {
		return implode(
			', ',
			array_filter(
				[
					self::get_core_block_category_label( $slug ),
					self::get_custom_block_category_labels( $slug ),
				]
			)
		);
	}

	/**
	 * Return the title of the block category.
	 *
	 * @param string $slug The slug of the category.
	 *
	 * @source https://developer.wordpress.org/reference/functions/get_default_block_categories/
	 *
	 * @return string
	 */
	private static function get_core_block_category_label( string $slug ): string {
		static $default_categories = null;
		if ( empty( $default_categories ) ) {
			$default_categories = get_default_block_categories();
		}

		$index = array_search( $slug, array_column( $default_categories, 'slug' ), true );

		if ( is_bool( $index ) ) {
			return '';
		}

		return $default_categories[ $index ]['title'];
	}

	/**
	 * Return the title of the block category of the custom blocks.
	 *
	 * @param string $slug The slug of the category.
	 *
	 * @source https://developer.wordpress.org/reference/functions/get_default_block_categories/
	 *
	 * @return string
	 */
	private static function get_custom_block_category_labels( string $slug ): string {
		// Start duplicate code from class-frontend.php, need to refactor this.

		// phpcs:ignore Generic.Commenting.Todo.TaskFound -- Before production this has to be done.
		// @todo: Check how to re-use `register_custom_block_category` from class-frontend.php .
		$ncb_categories = [
			[
				'slug'  => 'nlds-community-blocks',
				'title' => __( 'NLDS Community Blocks', 'nlds-community-blocks' ),
				'icon'  => null,
			],
			[
				'slug'  => 'nlds-community-blocks-layout',
				'title' => __( 'NLDS Community Layout Blocks', 'nlds-community-blocks' ),
				'icon'  => null,
			],
		];

		foreach ( glob( NCB_ABSPATH . NCB_ASSETS_DIR . 'blocks/*', GLOB_ONLYDIR ) as $ncb_community_path ) {
			if ( empty( $ncb_community_path ) ) {
				continue;
			}

			$ncb_exploded_community_path = explode( '/', $ncb_community_path );
			$ncb_community               = end( $ncb_exploded_community_path );

			if ( 'nlds' === $ncb_community ) {
				continue;
			}

			$ncb_categories[] = [
				/* translators: %s Community slug. */
				'slug'  => sprintf( 'nlds-community-%s-blocks', esc_attr( $ncb_community ) ),
				/* translators: %s Community slug. */
				'title' => sprintf( _x( 'NLDS %s Community Blocks', 'Register custom block category', 'nlds-community-blocks' ), esc_attr( ucfirst( $ncb_community ) ) ),
				'icon'  => null,
			];
			$ncb_categories[] = [
				/* translators: %s Community slug. */
				'slug'  => sprintf( 'nlds-community-%s-blocks-layout', esc_attr( $ncb_community ) ),
				/* translators: %s Community slug. */
				'title' => sprintf( _x( 'NLDS %s Community Layout Blocks', 'Register custom block category', 'nlds-community-blocks' ), esc_attr( ucfirst( $ncb_community ) ) ),
				'icon'  => null,
			];
		}
		// End of duplicated code.

		$index = array_search( $slug, array_column( $ncb_categories, 'slug' ), true );

		if ( is_bool( $index ) ) {
			return '';
		}

		return $ncb_categories[ $index ]['title'];
	}

	/**
	 * Extend body classes of the editor.
	 *
	 * @param string $classes String of classes.
	 *
	 * @return string
	 */
	public function ncb_editor_body_class_by_community_theme( string $classes ): string {
		if ( ! get_current_screen()->is_block_editor ) {
			return $classes;
		}

		$ncb_exploded_classes = explode( ' ', $classes );
		$ncb_theme            = esc_attr( get_option( 'ncb_organisation', '' ) );
		if ( ! empty( $ncb_theme ) ) {
			$ncb_exploded_classes[] = "$ncb_theme-theme";
		}

		return implode( ' ', array_filter( $ncb_exploded_classes ) );
	}
}
