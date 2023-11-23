import { _x } from '@wordpress/i18n';
import { useLayoutEffect, useMemo } from '@wordpress/element';
import { BlockControls, RichText, MediaPlaceholder } from '@wordpress/block-editor';
import { ToolbarGroup } from '@wordpress/components';
import classNames from 'classnames';
import { useSelect } from '@wordpress/data';
import { ReactComponent as DownloadIcon } from '../icons/download.svg';
import NCB_ImageDownloadControl from "./controls/ncb-image-download-control";
import NCB_ImageCaptionControl from "./controls/ncb-image-caption-control";
import NCB_ImageResetControl from "./controls/ncb-image-reset-control";
import NCB_ImageSizeControl from "../../../../../editor/ncb-image-size-control";
import NCB_DenhaagImageSizeByParent from "../../../../../editor/ncb-denhaag-image-size-by-parent";

export default function Edit( { attributes, setAttributes, clientId } ) {
	const media = useSelect( ( select ) => select( 'core' ).getMedia( attributes.image ), [ attributes.image ] );
	const isLoading = 'undefined' === typeof media;

	// get the image data
	const imageMetaData = useMemo( () => {
		if ( ! attributes.image || isLoading || ! media ) return null;

		const sizes = media.media_details.sizes;
		let srcObject = sizes?.[ attributes.size ];
		if ( ! srcObject ) {
			// Fallback to always existing image-size.
			srcObject = sizes?.large || sizes?.medium || sizes?.full;
		}

		return {
			src: srcObject?.source_url,
			alt: media?.alt_text,
			download: media?.title?.rendered.replaceAll( ' ', '-' ).toLowerCase(),
			type: `application/${ media?.media_type }`,
		};
	}, [ media, isLoading, attributes.image, attributes.size ] );

	useLayoutEffect( () => {
		// Remove `<meta charset="utf-8">` from copy-and-paste actions.
		if ( !! attributes.caption && !! attributes.caption.includes( '<meta charset="utf-8">' ) ) {
			setAttributes( { caption: attributes.caption.replace( /<meta charSet="utf-8">/gmi, '' ).trim() } );
		}
	}, [] );

  const sizeByParent = NCB_DenhaagImageSizeByParent( clientId, media );
  useLayoutEffect( () => {
    if (!!attributes.image && sizeByParent !== attributes.size) {
      setAttributes( {size: sizeByParent} );
    }
  }, [sizeByParent, attributes.image, media] );

	const _CLASSES = useMemo( () => {
		return {
			root: classNames( 'denhaag-image', {
				[ 'denhaag-image--has-image' ]: attributes.image,
				[ 'denhaag-image--no-image' ]: ! attributes.image,
				[ 'denhaag-image--loading' ]: !! isLoading,
			} ),
			image: classNames( 'denhaag-image__image', {
				[`denhaag-image--${attributes.size}`]: !! attributes.size
			} ),
			figcaption: 'denhaag-image__figcaption',
			figcaptionText: 'denhaag-image__figcaption-text',
			figcaptionDownload: 'denhaag-image__figcaption-download',
			icon: 'denhaag-image__icon',
			label: 'denhaag-image__download-text'
		};
	}, [ attributes.image, attributes.size, isLoading ] );

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<NCB_ImageCaptionControl
						value={ attributes.hasCaption }
						isDisabled={ ! attributes.image }
						setAttributes={ setAttributes }
					/>
					<NCB_ImageDownloadControl
						value={ attributes.download }
						isDisabled={ ! attributes.image }
						setAttributes={ setAttributes }
					/>
					<NCB_ImageSizeControl
						value={ attributes.size }
						isDisabled={ ! attributes.image }
						setAttributes={ setAttributes }
						media={ media }
					/>
				</ToolbarGroup>
				<NCB_ImageResetControl
					isDisabled={ ! attributes.image }
					setAttributes={ setAttributes }
				/>
			</BlockControls>

			<figure className={ _CLASSES.root }>
				{ ! attributes.image && (
					<MediaPlaceholder
						allowedTypes={ [ 'image' ] }
						multiple={ false }
						value={ attributes.image }
						onSelect={ ( media ) => setAttributes( { image: media.id } ) }
					/>
				) }
				{ imageMetaData && (
					<img
						className={ _CLASSES.image }
						src={ imageMetaData?.src }
						alt={ imageMetaData?.alt }
						loading="lazy"
					/>
				) }
				{ ( !! attributes.caption || !! attributes.hasCaption || !! attributes.download ) && (
					<>
						<figcaption className={ _CLASSES.figcaption }>
							{ !! attributes.hasCaption && (
								<RichText
									className={ _CLASSES.figcaptionText }
									withoutInteractiveFormatting
									placeholder={
										_x(
											'Place your caption here',
											'ncb-denhaag/image: Caption placeholder',
											'nlds-community-blocks'
										)
									}
									value={ attributes.caption }
									onChange={ ( text ) => setAttributes( { caption: text.replace( /<[^>]*>?/gm, '' ) } ) }
									allowedFormats={ [] }
								/>
							) }
							{ !! attributes.download && !! media && !! imageMetaData && (
								<a
									className={ _CLASSES.figcaptionDownload }
									onClick={ ( e ) => e.preventDefault() }
									href={ media?.source_url }
									download={ imageMetaData?.download }
									type={ imageMetaData?.type }
								>
									<DownloadIcon className={ _CLASSES.icon } />
									<span className={ _CLASSES.label }>
										{ _x( 'Download image', 'ncb-denhaag/image: Download label', 'nlds-community-blocks' ) }
									</span>
								</a>
							) }
						</figcaption>
						<hr className="denhaag-divider" role="presentation" />
					</>
				) }
			</figure>
		</>
	);
}
