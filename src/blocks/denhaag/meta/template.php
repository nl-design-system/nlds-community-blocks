<?php
/**
 * Template for the Meta Block.
 *
 * Build the HTML for the Meta Block.
 *
 * @design     https://www.figma.com/file/JpoY3waVoQGlLQzQXTL9nn/Den-Haag-Design-System?node-id=8515-28504&t=WaY0pnpGa0MZMT1o-0
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Meta
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content    The Block content.
 * @global array  $attributes The Block attributes.
 */

// Receive an array with data to show for this post_type.
$ncb_meta    = apply_filters( 'ncb_denhaag_meta', [], get_post_type(), get_the_ID() );
$ncb_buttons = apply_filters( 'ncb_denhaag_meta_buttons', get_post_type(), get_the_ID() );

if ( empty( $ncb_buttons['share'] ) && empty( $ncb_meta ) ) {
	the_title( '<h1 class="utrecht-heading-1">', '</h1>' );

	if ( ! empty( $attributes['excerpt'] ) && has_excerpt() ) :
		printf( '<p class="utrecht-paragraph utrecht-paragraph--lead">%s</p>', wp_kses_post( get_the_excerpt() ) );
	endif;

	// No need to proceed further in this file.
	return;
}



if ( ! empty( $ncb_buttons['share'] ) ) {
	$ncb_permalink  = get_permalink();
	$ncb_post_title = get_the_title();

	$ncb_share_links = [
		'whatsapp' => wp_parse_args(
			[
				/* translators: %s Social media platform name. */
				'aria-label' => sprintf( esc_attr_x( 'Share this page on %s', 'denhaag/meta social share aria-label', 'nlds-community-blocks' ), esc_attr( 'WhatsApp' ) ),
				'class'      => 'denhaag-button denhaag-button--icon-only',
			],
			ncb_link_attributes(
				[
					'opensInNewTab' => true,
					'url'           => 'https://web.whatsapp.com/send?text=' . $ncb_permalink . ' ' . $ncb_post_title,
				]
			)
		),
		'facebook' => wp_parse_args(
			[
				/* translators: %s Social media platform name. */
				'aria-label' => sprintf( esc_attr_x( 'Share this page on %s', 'denhaag/meta social share aria-label', 'nlds-community-blocks' ), esc_attr( 'Facebook' ) ),
				'class'      => 'denhaag-button denhaag-button--icon-only',
			],
			ncb_link_attributes(
				[
					'opensInNewTab' => true,
					'url'           => 'https://www.facebook.com/sharer/sharer.php?u=' . $ncb_permalink . '&t=' . $ncb_post_title,
				]
			)
		),
		'twitter'  => wp_parse_args(
			[
				/* translators: %s Social media platform name. */
				'aria-label' => sprintf( esc_attr_x( 'Share this page on %s', 'denhaag/meta social share aria-label', 'nlds-community-blocks' ), esc_attr( 'Twitter' ) ),
				'class'      => 'denhaag-button denhaag-button--icon-only',
			],
			ncb_link_attributes(
				[
					'opensInNewTab' => true,
					'url'           => 'https://twitter.com/intent/tweet?text=' . $ncb_post_title . '&url=' . $ncb_permalink,
				]
			)
		),
		'mail'     => wp_parse_args(
			[
				/* translators: %s share by email label. */
				'aria-label' => sprintf( esc_attr_x( 'Share this page by %s', 'denhaag/meta social share aria-label', 'nlds-community-blocks' ), esc_attr( 'E-mail' ) ),
				'class'      => 'denhaag-button denhaag-button--icon-only',
			],
			ncb_link_attributes(
				[
					'opensInNewTab' => true,
					'url'           => 'mailto:?subject=' . $ncb_post_title . '&body=' . $ncb_permalink,
				]
			)
		),
	];
}

?>
	<div class="denhaag-meta">
		<?php

		the_title( '<h1 class="utrecht-heading-1">', '</h1>' );

		if ( ! empty( $ncb_meta ) ) :
			$ncb_meta_counter = 0;
			$ncb_meta_count   = count( $ncb_meta ) - 1;
			?>
			<div class="denhaag-meta__meta">
				<?php
				foreach ( $ncb_meta as $ncb_meta_key => $ncb_meta_value ) :

					if ( is_object( $ncb_meta_value ) ) {
						$ncb_meta_value = (array) $ncb_meta_value;
					}

					printf(
						'<div class="denhaag-meta__meta-item denhaag-meta__meta-item--%s">%s</div>',
						esc_attr( $ncb_meta_key ),
						is_array( $ncb_meta_value )
							? wp_kses_post( implode( '', $ncb_meta_value ) )
							: wp_kses_post( $ncb_meta_value )
					);

					if ( $ncb_meta_count > 0 && $ncb_meta_counter !== $ncb_meta_count ) {
						echo '<hr class="denhaag-divider denhaag-divider--vertical" role="presentation" />';
					}

					++$ncb_meta_counter;
				endforeach;
				?>
			</div>
			<?php
		endif;
		if ( ! empty( $ncb_buttons ) ) :
			?>
			<div class="denhaag-meta__buttons">
				<?php if ( ! empty( $ncb_buttons['share'] ) ) : ?>
					<div class="denhaag-meta__share">
						<?php
						printf(
							'<button %1$s><span class="denhaag-button__icon">%2$s</span>%3$s</button>',
							ncb_to_dom_attributes(
								[
									'id'            => 'denhaag-share-button',
									'aria-expanded' => 'false',
									'aria-controls' => 'denhaag-meta-share',
									'aria-haspopup' => 'true',
									'class'         => [
										'denhaag-button',
										'denhaag-button--secondary-action',
										'denhaag-button--start-icon',
									],
								]
							),
							wp_kses_post( ncb_denhaag_icon( 'share' ) ),
							esc_attr_x( 'Share', 'denhaag/meta: Share button label', 'nlds-community-blocks' )
						);
						?>
						<div id="denhaag-meta-share" class="denhaag-meta__share-wrapper">
							<?php
							foreach ( $ncb_share_links as $ncb_share_link_type => $ncb_share_link_attributes ) :
								printf(
									'<a %s><span class="denhaag-button__icon">%s</span></a>',
									ncb_to_dom_attributes( $ncb_share_link_attributes ),
									wp_kses_post( ncb_denhaag_icon( $ncb_share_link_type ) )
								);
							endforeach;
							?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
<?php

if ( ! empty( $attributes['excerpt'] ) && has_excerpt() ) :
	printf( '<p class="utrecht-paragraph utrecht-paragraph--lead">%s</p>', wp_kses_post( get_the_excerpt() ) );
endif;
