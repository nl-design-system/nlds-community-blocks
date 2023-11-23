import classNames from 'classnames';
import { useSelect, dispatch } from '@wordpress/data';
import { postFeaturedImage } from '@wordpress/icons';
import { BlockControls, MediaPlaceholder } from "@wordpress/block-editor";
import {Notice, Spinner} from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import {useLayoutEffect, useMemo} from "@wordpress/element";
import NCB_ImageSizeControl from "../../../../../editor/ncb-image-size-control";
import ncb_DenhaagImageSizeByParent from "../../../../../editor/ncb-denhaag-image-size-by-parent";

export default function edit( { attributes, setAttributes, clientId } ) {
  const {supported: isSupported} = window.ncb_editor_variables?.denhaag['featured-image'];

	/**
	 * Retrieves the post featured image ID.
	 * Returns `0` is not image is set as featured image.
	 *
	 * @type {import("../../types").UseSelectReturn<function(*): *>}
	 */
	const imageId = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return getEditedPostAttribute( 'featured_media' );
	}, [] );

	/**
	 * Returns media-object (same as the WP Rest Media endpoint.
	 *
	 * @type {import("../../types").UseSelectReturn<function(*): *>}
	 */
	const media = useSelect( ( select ) => select( 'core' ).getMedia( imageId ), [ imageId ] );

	/**
	 * Returns object of classNames for all elements.
	 *
	 * @type {{image: string, figure: string, root: string}}
	 * @private
   */
  const _CLASSES = useMemo( () => {
    return {
      root: classNames( 'denhaag-featured-image', {
        ['denhaag-featured-image--loading']: 'object' !== typeof media && !!isSupported,
        ['denhaag-featured-image--not-supported']: !isSupported,
      } ),
      figure: 'denhaag-image',
      image: `denhaag-featured-image__image`
    };
  }, [media, isSupported] );


  /**
   * Returns object of image attributes.
	 *
	 * @type {{src: string, alt: string}}
	 */
	const imageAttributes = useMemo( () => {
		if ( ! media ) return null;

		const sizes = media?.media_details?.sizes;
		let srcObject = sizes?.[ attributes.size ];
		if ( ! srcObject ) {
			// Fallback to always existing image-size.
			srcObject = sizes?.large || sizes?.medium || sizes?.full;
		}

		return {
			src: srcObject?.source_url,
			alt: media?.alt_text,
		};
	}, [ media, attributes.size ] );

  const sizeByParent = ncb_DenhaagImageSizeByParent( clientId, media );
  useLayoutEffect( () => {
    if (!!attributes.image && sizeByParent !== attributes.size) {
      setAttributes( {size: sizeByParent} );
    }
  }, [sizeByParent, attributes.image, media] );

  if (!isSupported) {
    return (
      <Notice status="warning" isDismissible={false}>{_x(
        'This post type does not support a featured image',
        'denhaag/featured-image: notice',
        'nlds-community-blocks'
      )}</Notice>
    )
  }

	if ( 0 === imageId ) {
		return (
			<MediaPlaceholder
				className={ classNames( 'denhaag-featured-image', `denhaag-featured-image--empty` ) }
				multiple={ false }
				allowedTypes={ [ 'image' ] }
				icon={ postFeaturedImage }
				labels={ {
					title: _x( 'Featured Image', 'denhaag/featured-image: MediaPlaceholder', 'nlds-community-blocks' ),
					instructions: _x( 'Upload an image by the button or select one from your media library.', 'denhaag/featured-image: MediaPlaceholder', 'nlds-community-blocks' ),
				} }
				onSelect={ ( media ) => dispatch( 'core/editor' ).editPost( { featured_media: media.id } ) }
			/>
		);
	}

	if ( 'object' !== typeof media ) {
		return (
			<div className={ _CLASSES.root }>
				<Spinner />
			</div>
		);
	}

	return (
		<>
			<BlockControls>
				<NCB_ImageSizeControl
					value={ attributes.size }
					isDisabled={ ! imageId }
					setAttributes={ setAttributes }
					media={ media }
				/>
			</BlockControls>

			<div className={ _CLASSES.root }>
				<figure className={ _CLASSES.figure }>
					<img className={ _CLASSES.image }
						 src={ imageAttributes?.src }
						 alt={ imageAttributes.alt }
						 loading="lazy"
					/>
				</figure>
			</div>
		</>
	);
}
