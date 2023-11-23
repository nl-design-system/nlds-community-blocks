import {useSelect} from '@wordpress/data';

const ncb_DenhaagImageSizeByParent = (clientId, media) => {
  return useSelect( (select) => {
      const parents = select( 'core/block-editor' ).getBlockParents( clientId );

      if (!parents || 0 === parents.length || !media || 0 === Object.keys( media ) || !media.hasOwnProperty( 'media_details' )) {
        if (media?.media_details?.sizes.hasOwnProperty( 'large' )) {
          return 'large';
        }
        return 'full';
      }

      if ('denhaag/column' === select( 'core/block-editor' ).getBlockName( parents[parents.length - 1] )) {

        let blockIndex = 0;

        // `denhaag/column` is ALWAYS in `denhaag/columns`, therefore we do `- 2` instead of `- 1`.
        const parentAttributes = select( 'core/block-editor' ).getBlockAttributes( parents[parents.length - 2] );

        // Switch through layouts.
        switch (parentAttributes?.layout) {
          case 'single':
            if (media?.media_details?.sizes.hasOwnProperty( 'large' )) {
              return 'large';
            }
            return 'full';
          case 'fifty-fifty':
          case 'one-third-one-third-one-third':
            if (media?.media_details?.sizes.hasOwnProperty( 'medium' )) {
              return 'medium';
            }
            return 'full';
          case 'one-third-two-third':
            // Get blockIndex of parent `denhaag/column`.
            blockIndex = select( 'core/block-editor' ).getBlock( parents[parents.length - 2] ).innerBlocks.findIndex( object => object.clientId === parents[parents.length - 1], [parents[parents.length - 2]] );

            // Return `medium_large` for the two-third.
            if (1 === blockIndex) {
              if (media?.media_details?.sizes.hasOwnProperty( 'medium_large' )) {
                return 'medium_large';
              }
              return 'full';
            }

            // Return `medium` for the one-third.
            if (0 === blockIndex) {
              if (media?.media_details?.sizes.hasOwnProperty( 'medium' )) {
                return 'medium';
              }
              return 'full';
            }

            // Return large as default.
            return 'large';
          case 'two-third-one-third':
            // Get blockIndex of parent `denhaag/column`.
            blockIndex = select( 'core/block-editor' ).getBlock( parents[parents.length - 2] ).innerBlocks.findIndex( object => object.clientId === parents[parents.length - 1], [parents[parents.length - 1]] );

            // Return `medium_large` for the two-third.
            if (0 === blockIndex) {
              if (media?.media_details?.sizes.hasOwnProperty( 'medium_large' )) {
                return 'medium_large';
              }
              return 'full';
            }

            // Return `medium` for the one-third.
            if (1 === blockIndex) {
              if (media?.media_details?.sizes.hasOwnProperty( 'medium' )) {
                return 'medium';
              }
              return 'full';
            }

            // Return large as default.
            if (media?.media_details?.sizes.hasOwnProperty( 'large' )) {
              return 'large';
            }
            return 'full';
        }
      }

      // Return `large` as default.
      if (media?.media_details?.sizes.hasOwnProperty( 'large' )) {
        return 'large';
      }
      return 'full';
    },
    [
      clientId,
      media
    ]
  );
};

export default ncb_DenhaagImageSizeByParent;
