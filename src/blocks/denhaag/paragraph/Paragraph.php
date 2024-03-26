<?php

function render_paragraph($attributes, $content) {

		if ( ! empty( $content ) ) {

			// phpcs:ignore Generic.Commenting.Todo.TaskFound
			// @todo: the `ncb_filter_denhaag_content_links()` must be applied to the REST endpoint.

			// Filter for links within the content.
			$content = ncb_filter_denhaag_content_links( $content );
		}

        return wp_kses_post( $content );
}
