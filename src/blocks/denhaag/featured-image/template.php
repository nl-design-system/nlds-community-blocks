<?php
/**
 * Template for the Featured_Image Block.
 * Build the HTML for the Featured_Image Block.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Featured_Image
 * @since      1.0.0
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 * @global array $attributes The Block attributes.
 */
if ( ! has_post_thumbnail() ) {
	return;
}

$ncb_size = ! empty( $attributes['size'] ) ? esc_attr( $attributes['size'] ) : 'full';

?>
<figure class="denhaag-image">
	<?php the_post_thumbnail( $ncb_size, [ 'class' => 'denhaag-image__image' ] ); ?>
</figure>
