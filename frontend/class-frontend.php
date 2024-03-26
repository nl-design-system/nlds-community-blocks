<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Frontend
 */

namespace Nlds_Community_Blocks\Frontend;

use Nlds_Community_Blocks\Includes\Plugin;
use stdClass;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines public-facing functionality of the plugin.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Frontend
 * @author     Richard Korthuis <richardkorthuis@acato.nl>
 */
class Frontend {

	/**
	 * Array of used blocks in widget areas.
	 *
	 * @var array
	 */
	private $widget_area_blocks = [];

	/**
	 * Register a custom block category.
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array Array of block categories.
	 */
	public function register_custom_block_category( $categories ) {

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

		return array_merge(
			$categories,
			$ncb_categories
		);
	}

	/**
	 * Enqueue assets for dynamic blocks for the frontend.
	 */
	public function enqueue_block_assets() {
		if ( ! Plugin::has_resource( NCB_ABSPATH . NCB_ASSETS_DIR . 'mix-manifest.json' ) ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( 'nlds-community-blocks (mix-manifest.json) isn`t found. Forgot to run `pnpm run build` or `pnpm exec mix build --production` ?' );

			return false;
		}

		$ncb_theme = esc_attr( get_option( 'ncb_municipality', 'denhaag' ) );
		if ( ! empty( $ncb_theme ) && 'none' !== $ncb_theme && Plugin::has_resource( NCB_ABSPATH . NCB_ASSETS_DIR . "client/tokens/ncb-$ncb_theme-tokens.css" ) ) {
			wp_enqueue_style(
				"ncb-$ncb_theme-tokens",
				ncb_mix( "client/tokens/ncb-$ncb_theme-tokens.css" ),
				[],
				self::get_package_version( self::get_package_name_from_block_name( "@gemeente-{$ncb_theme}/design-tokens-components" ) )
			);
		}

		// Loop through all blocks and check for existing `style.css` and enqueue it.
		$ncb_communities = glob( NCB_ABSPATH . NCB_ASSETS_DIR . 'blocks/*/*', GLOB_ONLYDIR );
		$ncb_prefix      = 'ncb-';

		if ( ! empty( $ncb_communities ) ) {
			foreach ( $ncb_communities as $ncb_community ) {
				if ( ! Plugin::has_resource( $ncb_community . '/style.css' ) ) {
					// Continue if the file is empty.
					continue;
				}

				$ncb_block_meta = json_decode( ncb_file_get_contents( $ncb_community . '/block.json' ), true );
				$ncb_handle     = str_replace( '/', '-', $ncb_block_meta['name'] );
				$ncb_build_dir  = trailingslashit( 'blocks/' . str_replace( $ncb_prefix, '', $ncb_block_meta['name'] ) );

				// Get block version, priority to package-lock.json version.
				$ncb_package_version = self::get_package_version( self::get_package_name_from_block_name( $ncb_block_meta['name'] ) );
				if( empty( $ncb_package_version ) && ! empty( $ncb_block_meta['version'] ) ) {
					$ncb_package_version = esc_attr( $ncb_block_meta['version'] );
				} else if ( empty( $ncb_package_version ) ) {
					$ncb_package_version = filemtime( $ncb_community . '/style.css' );
				}

				// Enqueue will be done from the `block.json`.
				wp_register_style( "$ncb_handle-block", ncb_mix( $ncb_build_dir . 'style.css' ), [ "ncb-$ncb_theme-tokens" ], $ncb_package_version );

				if ( ! Plugin::has_resource( $ncb_community . '/client.js' ) ) {
					// Continue if no client.js is found.
					continue;
				}

				// Enqueue script (optional).
				// Enqueue will be done from the `block.json`.
				wp_register_script( "$ncb_handle-block", ncb_mix( $ncb_build_dir . 'client.js' ), false, ! empty( $ncb_block_meta['version'] ) ? esc_attr( $ncb_block_meta['version'] ) : filemtime( $ncb_community . '/client.js' ), true );
			}
		}

		// Register shared block styles for the blocks.
		$ncb_shared_block_styles = glob( NCB_ABSPATH . NCB_ASSETS_DIR . 'client/blocks/*.css' );
		if ( ! empty( $ncb_shared_block_styles ) ) {
			foreach ( $ncb_shared_block_styles as $ncb_shared_block_style ) {

				if ( ! Plugin::has_resource( $ncb_shared_block_style ) ) {
					// Continue if the file is empty.
					continue;
				}

				$ncb_parts = explode( '/', $ncb_shared_block_style );
				$ncb_file  = implode( '-', array_slice( $ncb_parts, - 1 ) );
				$ncb_name  = substr( $ncb_file, 0, - 4 );

				// Enqueue will be done from the `block.json` if it's needed by a block as dependency.
				wp_register_style(
					$ncb_name,
					ncb_mix( 'client/blocks/' . $ncb_file ),
					[ "ncb-$ncb_theme-tokens" ],
					self::get_package_version( self::get_package_name_from_block_name( $ncb_name ) ) ?: filemtime( $ncb_shared_block_style )
				);
			}
		}
	}

	/**
	 * Dequeue assets for core blocks for the frontend when set in the settings page.
	 *
	 * @return void
	 */
	public function dequeue_block_assets() {
		$ncb_selected_default_block_styles = get_option( 'ncb_dequeue_default_block_styles', [] );
		if ( empty( $ncb_selected_default_block_styles ) ) {
			return;
		}

		foreach ( array_filter( $ncb_selected_default_block_styles ) as $ncb_selected_default_block_style ) {
			wp_dequeue_style( $ncb_selected_default_block_style );
		}
	}

	/**
	 * Enhance the `wp_kses_post()` with allowed HTML for the Den Haag community.
	 *
	 * @param {array} $allowed_tags Array of all allowed tags.
	 *
	 * @return mixed
	 */
	public function ncb_denhaag_extend_wp_kses_posts( $allowed_tags ) {
		/*
		 * Index:
		 * 1. Buttons,
		 * 2. SVG & SVG inner elements;
		 * 3. Time
		 * 4. Table
		 */

		// 1. Buttons.
		$allowed_tags['button'] = wp_parse_args(
			[
				'aria-*'        => true,
				'aria-controls' => true, // This is excluded from `aria-*`.
			],
			array_key_exists( 'button', $allowed_tags ) ? $allowed_tags['button'] : []
		);

		// 2. SVG & SVG inner elements.
		$allowed_tags['svg'] = wp_parse_args(
			[
				'aria-hidden'     => true,
				'aria-label'      => true,
				'aria-labelledby' => true,
				'class'           => true,
				'id'              => true,
				'height'          => true,
				'fill'            => true,
				'focusable'       => true,
				'shape-rendering' => true,
				'style'           => true,
				'role'            => true,
				'viewbox'         => true, // Must be lowercase!
				'width'           => true,
				'xmlns'           => true,
			],
			array_key_exists( 'svg', $allowed_tags ) ? $allowed_tags['svg'] : []
		);

		$allowed_tags['path'] = wp_parse_args(
			[
				'aria-*'          => true,
				'shape-rendering' => true,
				'd'               => true,
				'fill'            => true,
				'focusable'       => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'style'           => true,
			],
			array_key_exists( 'path', $allowed_tags ) ? $allowed_tags['path'] : []
		);

		$allowed_tags['use'] = wp_parse_args(
			[
				'xlink:href'      => true,
				'href'            => true,
				'y'               => true,
				'x'               => true,
				'height'          => true,
				'width'           => true,

				// May overwrite `path` variables.
				'aria-*'          => true,
				'shape-rendering' => true,
				'd'               => true,
				'fill'            => true,
				'focusable'       => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'style'           => true,
			],
			array_key_exists( 'use', $allowed_tags ) ? $allowed_tags['use'] : []
		);

		// 3. Time.
		$allowed_tags['time'] = wp_parse_args(
			[
				'id'       => true,
				'datetime' => true,
				'aria-*'   => true,
			],
			array_key_exists( 'time', $allowed_tags ) ? $allowed_tags['time'] : []
		);

		// 4. Table.
		$allowed_tags['table'] = wp_parse_args(
			[
				'id'     => true,
				'class'  => true,
				'aria-*' => true,
			],
			array_key_exists( 'table', $allowed_tags ) ? $allowed_tags['table'] : []
		);
		$allowed_tags['tr']    = wp_parse_args(
			[
				'id'    => true,
				'class' => true,
			],
			array_key_exists( 'tr', $allowed_tags ) ? $allowed_tags['tr'] : []
		);
		$allowed_tags['th']    = wp_parse_args(
			[
				'id'        => true,
				'class'     => true,
				'scope'     => true,
				'aria-sort' => true,
			],
			array_key_exists( 'th', $allowed_tags ) ? $allowed_tags['th'] : []
		);
		$allowed_tags['td']    = wp_parse_args(
			[
				'id'    => true,
				'class' => true,
				'scope' => true,
			],
			array_key_exists( 'td', $allowed_tags ) ? $allowed_tags['td'] : []
		);

		return $allowed_tags;
	}

	/**
	 * Set <body> class based on the selected theme.
	 *
	 * @param {array} $classes An array of classes.
	 *
	 * @return mixed
	 */
	public function ncb_body_class_by_community_theme( $classes ) {
		$ncb_theme = esc_attr( get_option( 'ncb_municipality', 'denhaag' ) );
		if ( ! empty( $ncb_theme ) ) {
			$classes[] = "$ncb_theme-theme";
		}

		return array_unique( array_filter( $classes ) );
	}

	/**
	 * Get all blockNames from the provided items-array.
	 *
	 * @param array $items       Array of blocks.
	 * @param array $block_names Array of block names, which will be returned.
	 *
	 * @return mixed
	 */
	private static function get_block_names( $items, &$block_names ) {
		foreach ( $items as $item ) {
			$block_names[] = $item['blockName'];

			if ( ! empty( $item['innerBlocks'] ) ) {
				self::get_block_names( $item['innerBlocks'], $block_names );
			}
		}

		return $block_names;
	}

	/**
	 * Return array of used block names.
	 *
	 * Source: https://github.com/WordPress/gutenberg/issues/44616#issuecomment-1265664292
	 *
	 * @return array
	 */
	private static function widget_area_blocks() {
		$ncb_get_widgets = wp_get_sidebars_widgets();
		unset( $ncb_get_widgets['wp_inactive_widgets'] );

		global $wp_widget_factory;

		$blocks_array = [];
		foreach ( array_filter( $ncb_get_widgets ) as $widget_area => $widgets ) {
			foreach ( $widgets as $widget_id ) {
				$parsed_id     = wp_parse_widget_id( $widget_id );
				$widget_object = $wp_widget_factory->get_widget_object( $parsed_id['id_base'] );
				if ( $widget_object && isset( $parsed_id['number'] ) ) {

					$all_instances = $widget_object->get_settings();
					$instance      = $all_instances[ $parsed_id['number'] ];
					// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					$serialized_instance = serialize( $instance );
					// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					$prepared['instance']['encoded'] = base64_encode( $serialized_instance );
					$prepared['instance']['hash']    = wp_hash( $serialized_instance );

					// Use new stdClass so that JSON result is {} and not [].
					$prepared['instance']['raw'] = empty( $instance ) ? new stdClass() : $instance;
					$blocks_array[]              = parse_blocks( $prepared['instance']['raw']['content'] );
				}
			}
		}

		if ( empty( array_filter( $blocks_array ) ) ) {
			return [];
		}

		$block_names = [];
		foreach ( $blocks_array as $block_group ) {
			self::get_block_names( $block_group, $block_names );
		}

		return array_unique( $block_names );
	}

	/**
	 * Return true/false
	 *
	 * @param string|array $block_names_to_find String or Array of used blockNames.
	 *
	 * @return bool
	 */
	private function has_blocks_in_widget_area( $block_names_to_find ) {
		if ( empty( $this->widget_area_blocks ) || empty( $block_names_to_find ) ) {
			return false;
		}

		if ( is_string( $block_names_to_find ) ) {
			return in_array( $block_names_to_find, array_unique( $this->widget_area_blocks ), true );
		}

		if ( is_array( $block_names_to_find ) ) {
			return count( array_intersect( $block_names_to_find, array_unique( $this->widget_area_blocks ) ) ) > 0;
		}

		return false;
	}

	/**
	 * Return SVG templates used within specific blocks.
	 *
	 * @return void
	 * @throws \DOMException Extend dom only.
	 */
	public function ncb_action_community_icons_based_on_block() {
		$icons = [];

		$this->widget_area_blocks = self::widget_area_blocks();

		/*
		 * Each block that has an icon.
		 */
		if ( self::has_block_in_editor_or_widgets( [
			'ncb-denhaag/accordion',
			'ncb-denhaag/accordion-item',
		] ) ) {
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-chevron-down-icon',
				'd'     => 'M5.293 8.293a1 1 0 011.414 0L12 13.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z',
				'fill'  => 'currentColor',
			];
		}

		if ( has_block( 'ncb-denhaag/image' ) || self::has_blocks_in_widget_area( 'ncb-denhaag/image' ) ) {
			$icons['denhaag'][] = [
				'@type'           => 'path',
				'id'              => 'ncb-denhaag-download-icon',
				'd'               => 'M21 15v4a2 2 0 0 1-2 2H5a1.9999 1.9999 0 0 1-2-2v-4m4-5 5 5 5-5m-5 5V3',
				'stroke'          => 'currentColor',
				'stroke-linecap'  => 'round',
				'stroke-linejoin' => 'round',
				'stroke-width'    => 2,
			];
		}

		if ( self::has_block_in_editor_or_widgets( [
			'ncb-denhaag/button',
			'ncb-denhaag/description-list',
			'ncb-denhaag/paragraph',
			'ncb-denhaag/link-item',
		] ) ) {
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-external-icon',
				'd'     => 'M14 5C13.4477 5 13 4.55228 13 4C13 3.44772 13.4477 3 14 3H20C20.2652 3 20.5196 3.10536 20.7071 3.29289C20.8946 3.48043 21 3.73478 21 4L21 10C21 10.5523 20.5523 11 20 11C19.4477 11 19 10.5523 19 10L19 6.41422L9.70711 15.7071C9.31658 16.0976 8.68342 16.0976 8.29289 15.7071C7.90237 15.3166 7.90237 14.6834 8.29289 14.2929L17.5858 5H14ZM3 7C3 5.89543 3.89543 5 5 5H10C10.5523 5 11 5.44772 11 6C11 6.55228 10.5523 7 10 7H5V19H17V14C17 13.4477 17.4477 13 18 13C18.5523 13 19 13.4477 19 14V19C19 20.1046 18.1046 21 17 21H5C3.89543 21 3 20.1046 3 19V7Z',
				'fill'  => 'currentColor',
			];
		}

		if ( self::has_block_in_editor_or_widgets( ['ncb-denhaag/button'] ) ) {
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-arrow-right-icon',
				'd'     => 'M12.293 5.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L16.586 13H5a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z',
				'fill'  => 'currentColor',
			];

			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-arrow-left-icon',
				'd'     => 'M11.707 18.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L7.414 11H19a1 1 0 110 2H7.414l4.293 4.293a1 1 0 010 1.414z',
				'fill'  => 'currentColor',
			];
		}

		if ( self::has_block_in_editor_or_widgets( [
			'ncb-denhaag/highlighted-links',
			'ncb-denhaag/link-group',
			'ncb-denhaag/link-item',
		] ) ) {
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-arrow-right-icon',
				'd'     => 'M12.293 5.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L16.586 13H5a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z',
				'fill'  => 'currentColor',
			];
		}

		if ( self::has_block_in_editor_or_widgets( [ 'ncb-denhaag/note' ] ) ) {
			$icons['denhaag'][] = [
				'@type' => 'g',
				'id'    => 'ncb-denhaag-warning-icon',
				[
					'@type'           => 'path',
					'd'               => 'm10.2898 4.3592-8.47 14.14a2 2 0 0 0 1.71 3h16.94a1.9997 1.9997 0 0 0 1.978-2.0055 2.0007 2.0007 0 0 0-.268-.9945l-8.47-14.14a2.0004 2.0004 0 0 0-1.71-.9627 1.9998 1.9998 0 0 0-1.71.9627Z',
					'fill'            => '#F18700',
					'stroke'          => '#F18700',
					'stroke-linecap'  => 'round',
					'stroke-linejoin' => 'round',
					'stroke-width'    => 2,
				],
				[
					'@type'           => 'path',
					'd'               => 'M12 17.5h.01m-.01-8v4',
					'fill'            => '#fff',
					'stroke'          => '#fff',
					'stroke-linecap'  => 'round',
					'stroke-linejoin' => 'round',
					'stroke-width'    => 2,

				],
			];
			$icons['denhaag'][] = [
				'@type' => 'g',
				'id'    => 'ncb-denhaag-info-icon',
				[
					'@type'           => 'path',
					'd'               => 'M12 22.5c5.5228 0 10-4.4772 10-10 0-5.5229-4.4772-10-10-10-5.5229 0-10 4.4771-10 10 0 5.5228 4.4771 10 10 10Z',
					'fill'            => '#1261A3',
					'stroke'          => '#1261A3',
					'stroke-linecap'  => 'round',
					'stroke-linejoin' => 'round',
					'stroke-width'    => 2,
				],
				[
					'@type'           => 'path',
					'd'               => 'M12 16.5v-4m0-4h.01',
					'fill'            => '#fff',
					'stroke'          => '#fff',
					'stroke-linecap'  => 'round',
					'stroke-linejoin' => 'round',
					'stroke-width'    => 2,
				],
			];
		}

		if ( self::has_block_in_editor_or_widgets( [
			'ncb-denhaag/social-links',
			'ncb-denhaag/social-link',
			'ncb-denhaag/meta',
		] ) ) {
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-whatsapp-icon',
				'd'     => 'M17.0398 14.1835C16.7639 14.046 15.4201 13.3888 15.1698 13.298C14.9196 13.2054 14.7363 13.1605 14.5529 13.4355C14.3723 13.7059 13.8462 14.3192 13.6876 14.5007C13.5272 14.6794 13.3677 14.6932 13.0954 14.5694C12.8204 14.4319 11.9377 14.1432 10.8927 13.2082C10.0787 12.4794 9.53233 11.5857 9.371 11.3107C9.2115 11.0357 9.35358 10.8844 9.49017 10.7469C9.61483 10.6232 9.76608 10.4307 9.90358 10.2675C10.0374 10.1016 10.0814 9.99158 10.1758 9.81283C10.2675 9.62033 10.2207 9.46908 10.1529 9.3325C10.0842 9.195 9.53692 7.8475 9.30775 7.31033C9.08775 6.775 8.86133 6.84283 8.69175 6.84283C8.53408 6.82908 8.35167 6.82908 8.16833 6.82908C7.985 6.82908 7.68892 6.89692 7.43775 7.15817C7.1875 7.43317 6.47983 8.09317 6.47983 9.42692C6.47983 10.7607 7.46067 12.0532 7.59725 12.2457C7.73383 12.4244 9.52683 15.1744 12.2723 16.3569C12.9268 16.6319 13.4364 16.7969 13.8343 16.9335C14.4888 17.1416 15.0855 17.1123 15.5576 17.0444C16.0837 16.961 17.1773 16.3835 17.4056 15.7373C17.6393 15.091 17.6393 14.5548 17.5706 14.431C17.5028 14.3073 17.3231 14.2385 17.0481 14.1148L17.0398 14.1835ZM12.0559 20.9375H12.0413C10.4188 20.9375 8.81092 20.4975 7.4075 19.6725L7.0775 19.4763L3.64 20.3701L4.56125 17.0288L4.34217 16.6851C3.43467 15.2404 2.9525 13.5767 2.9525 11.8634C2.9525 6.87217 7.03625 2.80217 12.066 2.80217C14.4988 2.80217 16.7823 3.75092 18.5019 5.46967C20.2207 7.17375 21.1685 9.45717 21.1685 11.8772C21.1648 16.8675 17.0802 20.9384 12.0614 20.9384L12.0559 20.9375ZM19.81 4.16158C17.72 2.14125 14.97 1 12.0413 1C6.00775 1 1.09533 5.8895 1.09258 11.9019C1.09258 13.8232 1.59583 15.6969 2.55467 17.3515L1 23L6.80708 21.4857C8.40758 22.3501 10.2079 22.8093 12.0413 22.8121H12.0467C18.083 22.8121 22.9973 17.9208 23 11.9074C23 8.99608 21.8633 6.25617 19.7962 4.19733',
				'fill'  => 'currentColor',
			];
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-facebook-icon',
				'd'     => 'M9.95635 12.8771V22H13.4919V12.8888H16.4388L16.8781 9.32564H13.4919V7.05718C13.4919 6.0265 13.7678 5.31877 15.185 5.31877H17V2.1384C16.6863 2.09521 15.6102 2 14.3583 2C11.7458 2 9.95635 3.65497 9.95635 6.69693V9.3227H7V12.8771H9.95635Z',
				'fill'  => 'currentColor',
			];
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-twitter-icon',
				'd'     => 'M21.9608 5.93575C21.2234 6.25991 20.4359 6.48073 19.6068 6.58156C20.4517 6.07241 21.1017 5.26995 21.4092 4.31249C20.6167 4.77497 19.7384 5.11162 18.8035 5.29911C18.0568 4.49998 16.9927 4 15.8111 4C13.547 4 11.7113 5.83576 11.7113 8.09733C11.7113 8.42232 11.7488 8.7348 11.8171 9.03396C8.4089 8.87313 5.38819 7.23653 3.36661 4.76247C3.01079 5.36411 2.81163 6.06325 2.81163 6.82488C2.81163 8.24982 3.5366 9.50227 4.63489 10.2381C3.96242 10.2164 3.32994 10.0314 2.7783 9.72476V9.77559C2.7783 11.763 4.18908 13.4204 6.0665 13.7979C5.72235 13.8904 5.35903 13.9404 4.98654 13.9404C4.72489 13.9404 4.47406 13.9154 4.22324 13.8688C4.74905 15.4962 6.26066 16.6828 8.05975 16.7161C6.65981 17.8153 4.88571 18.4702 2.97496 18.4702C2.64997 18.4702 2.32582 18.4511 2 18.4144C3.82409 19.576 5.97317 20.2552 8.29724 20.2552C15.8419 20.2552 19.9626 14.0087 19.9626 8.60064C19.9626 8.42648 19.9626 8.25066 19.9501 8.07566C20.7509 7.50152 21.45 6.77572 22 5.95242L21.9608 5.93575Z',
				'fill'  => 'currentColor',
			];
			$icons['denhaag'][] = [
				'@type' => 'g',
				'id'    => 'ncb-denhaag-linkedin-icon',
				[
					'@type' => 'path',
					'd'     => 'M17.86 22H21.9988L22 14.6681C22 11.0698 21.2244 8.30548 17.0309 8.30548C15.0127 8.30548 13.6618 9.41354 13.1063 10.4633H13.0528V8.64256H9.07698V22H13.2158V15.3901C13.2158 13.6475 13.5454 11.9621 15.7033 11.9621C17.8286 11.9621 17.86 13.9554 17.86 15.5044V22Z',
					'fill'  => 'currentColor',
				],
				[
					'@type' => 'path',
					'd'     => 'M2 4.40625C2 5.73476 3.07022 6.81484 4.40247 6.81484C5.72889 6.81484 6.8061 5.73476 6.8061 4.40625C6.8061 3.07891 5.73006 2 4.40247 2C3.07139 2 2 3.07891 2 4.40625Z',
					'fill'  => 'currentColor',
				],
				[
					'@type' => 'path',
					'd'     => 'M2.32724 22H6.4777V8.64256H2.32724V22Z',
					'fill'  => 'currentColor',
				],
			];
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-instagram-icon',
				'd'     => 'M12 2C14.717 2 15.056 2.01 16.122 2.06C17.187 2.11 17.912 2.277 18.55 2.525C19.21 2.779 19.766 3.123 20.322 3.678C20.8305 4.1779 21.224 4.78259 21.475 5.45C21.722 6.087 21.89 6.813 21.94 7.878C21.987 8.944 22 9.283 22 12C22 14.717 21.99 15.056 21.94 16.122C21.89 17.187 21.722 17.912 21.475 18.55C21.2247 19.2178 20.8311 19.8226 20.322 20.322C19.822 20.8303 19.2173 21.2238 18.55 21.475C17.913 21.722 17.187 21.89 16.122 21.94C15.056 21.987 14.717 22 12 22C9.283 22 8.944 21.99 7.878 21.94C6.813 21.89 6.088 21.722 5.45 21.475C4.78233 21.2245 4.17753 20.8309 3.678 20.322C3.16941 19.8222 2.77593 19.2175 2.525 18.55C2.277 17.913 2.11 17.187 2.06 16.122C2.013 15.056 2 14.717 2 12C2 9.283 2.01 8.944 2.06 7.878C2.11 6.812 2.277 6.088 2.525 5.45C2.77524 4.78218 3.1688 4.17732 3.678 3.678C4.17767 3.16923 4.78243 2.77573 5.45 2.525C6.088 2.277 6.812 2.11 7.878 2.06C8.944 2.013 9.283 2 12 2ZM12 7C10.6739 7 9.40215 7.52678 8.46447 8.46447C7.52678 9.40215 7 10.6739 7 12C7 13.3261 7.52678 14.5979 8.46447 15.5355C9.40215 16.4732 10.6739 17 12 17C13.3261 17 14.5979 16.4732 15.5355 15.5355C16.4732 14.5979 17 13.3261 17 12C17 10.6739 16.4732 9.40215 15.5355 8.46447C14.5979 7.52678 13.3261 7 12 7ZM18.5 6.75C18.5 6.41848 18.3683 6.10054 18.1339 5.86612C17.8995 5.6317 17.5815 5.5 17.25 5.5C16.9185 5.5 16.6005 5.6317 16.3661 5.86612C16.1317 6.10054 16 6.41848 16 6.75C16 7.08152 16.1317 7.39946 16.3661 7.63388C16.6005 7.8683 16.9185 8 17.25 8C17.5815 8 17.8995 7.8683 18.1339 7.63388C18.3683 7.39946 18.5 7.08152 18.5 6.75ZM12 9C12.7956 9 13.5587 9.31607 14.1213 9.87868C14.6839 10.4413 15 11.2044 15 12C15 12.7956 14.6839 13.5587 14.1213 14.1213C13.5587 14.6839 12.7956 15 12 15C11.2044 15 10.4413 14.6839 9.87868 14.1213C9.31607 13.5587 9 12.7956 9 12C9 11.2044 9.31607 10.4413 9.87868 9.87868C10.4413 9.31607 11.2044 9 12 9Z',
				'fill'  => 'currentColor',
			];

			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-youtube-icon',
				'd'     => 'M21.543 6.498C22 8.28 22 12 22 12C22 12 22 15.72 21.543 17.502C21.289 18.487 20.546 19.262 19.605 19.524C17.896 20 12 20 12 20C12 20 6.107 20 4.395 19.524C3.45 19.258 2.708 18.484 2.457 17.502C2 15.72 2 12 2 12C2 12 2 8.28 2.457 6.498C2.711 5.513 3.454 4.738 4.395 4.476C6.107 4 12 4 12 4C12 4 17.896 4 19.605 4.476C20.55 4.742 21.292 5.516 21.543 6.498ZM10 15.5L16 12L10 8.5V15.5Z',
				'fill'  => 'currentColor',
			];
		}

		if ( self::has_block_in_editor_or_widgets( [ 'ncb-denhaag/meta' ] ) ) {
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-calendar-icon',
				'd'     => 'M9 2c.5523 0 1 .4477 1 1v1h4V3c0-.5523.4477-1 1-1s1 .4477 1 1v1h3c1.1046 0 2 .8954 2 2v13c0 1.1046-.8954 2-2 2H5c-1.1046 0-2-.8954-2-2V6c0-1.1046.8954-2 2-2h3V3c0-.5523.4477-1 1-1ZM8 6H5v3h14V6h-3v1c0 .5523-.4477 1-1 1s-1-.4477-1-1V6h-4v1c0 .5523-.4477 1-1 1s-1-.4477-1-1V6Zm11 5H5v8h14v-8Z',
				'fill'  => 'currentColor',
			];
			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-mail-icon',
				'd'     => 'M2 6C2 4.89543 2.89543 4 4 4H20C21.1046 4 22 4.89543 22 6V18C22 19.1046 21.1046 20 20 20H4C2.89543 20 2 19.1046 2 18V6ZM5.51859 6L12 11.6712L18.4814 6H5.51859ZM20 7.32877L12.6585 13.7526C12.2815 14.0825 11.7185 14.0825 11.3415 13.7526L4 7.32877V18H20V7.32877Z',
				'fill'  => 'currentColor',
			];

			$icons['denhaag'][] = [
				'@type' => 'path',
				'id'    => 'ncb-denhaag-share-icon',
				'd'     => 'M11.2929 2.29289C11.6834 1.90237 12.3166 1.90237 12.7071 2.29289L15.7071 5.29289C16.0976 5.68342 16.0976 6.31658 15.7071 6.70711C15.3166 7.09763 14.6834 7.09763 14.2929 6.70711L13 5.41421V15C13 15.5523 12.5523 16 12 16C11.4477 16 11 15.5523 11 15V5.41421L9.70711 6.70711C9.31658 7.09763 8.68342 7.09763 8.29289 6.70711C7.90237 6.31658 7.90237 5.68342 8.29289 5.29289L11.2929 2.29289ZM4 11C4 9.89543 4.89543 9 6 9H8C8.55228 9 9 9.44772 9 10C9 10.5523 8.55228 11 8 11H6V20H18V11H16C15.4477 11 15 10.5523 15 10C15 9.44772 15.4477 9 16 9H18C19.1046 9 20 9.89543 20 11V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V11Z',
				'fill'  => 'currentColor',
			];
		}

		// Build SVG Sprites for each populated.
		foreach ( $icons as $municipality => $municipality_icons ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<!-- SVG Sprite for ' . esc_attr( $municipality ) . ' -->' . PHP_EOL . $this->build_svg_sprite( $municipality, $municipality_icons )->saveHTML() . PHP_EOL;
		}
	}

	/**
	 * Building SVG sprite for each municipality based on the used blocks.
	 *
	 * @param string $municipality The municipality where the SVG sprite is for.
	 * @param array  $icons        Array of icons to add.
	 *
	 * @return \DOMDocument The DOM Document.
	 * @throws \DOMException The DOM Exception.
	 */
	private function build_svg_sprite( $municipality, $icons ) {
		$svg_sprite      = new \DOMDocument();
		$svg_parent_node = $svg_sprite->createElementNS( 'http://www.w3.org/2000/svg', 'svg' );
		foreach ( $this->get_svg_properties( $municipality ) as $attribute_name => $attribute_value ) {
			$svg_parent_node->setAttribute( $attribute_name, $attribute_value );
		}
		$svg_sprite->appendChild( $svg_parent_node );

		foreach ( $icons as $icon ) {
			$this->add_svg_to_dom( $icon, $svg_sprite, $svg_parent_node );
		}

		return $svg_sprite;
	}

	/**
	 * Create element, set attributes and append to parent element.
	 *
	 * @param array        $attributes        Array of attributes.
	 * @param \DOMDocument $root_dom_document DOM document.
	 * @param \DOMNode     $parent_node       Parent element to append to.
	 *
	 * @return void
	 * @throws \DOMException The DOMException.
	 */
	private function add_svg_to_dom( $attributes, \DOMDocument $root_dom_document, \DOMNode $parent_node ) {
		$child_node = $root_dom_document->createElement( $attributes['@type'] ?? 'g' );
		unset( $attributes['@type'] );
		foreach ( $attributes as $attribute_name => $attribute_value ) {
			if ( is_scalar( $attribute_value ) ) {
				$child_node->setAttribute( $attribute_name, $attribute_value );
			}
			if ( is_array( $attribute_value ) ) {
				$this->add_svg_to_dom( $attribute_value, $root_dom_document, $child_node );
			}
		}
		$parent_node->appendChild( $child_node );
	}

	/**
	 * Return array of attributes for the base SVG.
	 *
	 * @param string $municipality The municipality where we need the default attributes from.
	 *
	 * @return array|string[]
	 */
	private function get_svg_properties( $municipality ) {
		$default_properties = [
			'denhaag' => [
				'width'           => '1em',
				'height'          => '1em',
				'viewBox'         => '0 0 24 24',
				'fill'            => 'none',
				'xmlns'           => 'http://www.w3.org/2000/svg',
				'class'           => 'denhaag-icon',
				'focusable'       => 'false',
				'aria-hidden'     => 'true',
				'shape-rendering' => 'auto',
				'style'           => 'display:none;',
			],
			'utrecht' => [
				'width'           => '1em',
				'height'          => '1em',
				'viewBox'         => '0 0 24 24',
				'fill'            => 'none',
				'xmlns'           => 'http://www.w3.org/2000/svg',
				'class'           => 'utrecht-icon',
				'focusable'       => 'false',
				'aria-hidden'     => 'true',
				'shape-rendering' => 'auto',
				'style'           => 'display:none;',
			],
		];

		return $default_properties[ $municipality ] ?? [];
	}

	/**
	 * Returns the package version of a script.
	 *
	 * @param string $handle Handle of the script to retrieve the version from.
	 *
	 * @return string
	 */
	private static function get_package_version( $handle ): string {
		if ( empty( $handle ) ) {
			return '';
		}

		static $lock;
		if ( empty( $lock ) ) {
			$lock = NCB_ABSPATH . 'package-lock.json';

			if ( ! file_exists( $lock ) ) {
				return '';
			}

			$lock = get_object_vars( json_decode(
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				file_get_contents( $lock )
			) );
		}

		if ( ! empty( $lock['dependencies']->{$handle} ) ) {
			return $lock['dependencies']->{$handle}->version;
		}

		return '';
	}

	/**
	 * Ge the package name from the block name.
	 *
	 * @param string $string The name of the block.
	 *
	 * @return string|null
	 */
	private static function get_package_name_from_block_name( $string ) {

		if( empty( $string )) {
			return null;
		}

		if ( str_starts_with( $string, '@gemeente-' ) ) {
			// Probably already formatted to the correct format.
			return $string;
		}

		preg_match( '/ncb-([a-zA-Z]+)[\/-]([a-zA-Z-]+)/i', $string, $matches );

		if ( empty( $matches ) || empty( $matches[1] ) || empty( $matches[2] ) ) {
			return null;
		}

		// Format to the correct format.
		return "@gemeente-{$matches[1]}/{$matches[2]}";
	}

	/**
	 * Returns boolean if one of the blocks is on the page.
	 * @param string[] $block_names Array of blocknames.
	 *
	 * @return bool
	 */
	private static function has_blocks( $block_names ) {
		if( empty( $block_names ) ) {
			return false;
		}

		if ( is_string( $block_names ) ) {
			return has_block( $block_names );
		}

		foreach( $block_names as $block_name) {
			$ncb_has_block = has_block( $block_name );

			if( $ncb_has_block ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Checks if block is used within the editor or widget area.
	 *
	 * @param string[] $block_names Array of blocknames.
	 *
	 * @return bool
	 */
	private function has_block_in_editor_or_widgets( $block_names ) {
		return self::has_blocks( $block_names ) || self::has_blocks_in_widget_area( $block_names );
	}
}
