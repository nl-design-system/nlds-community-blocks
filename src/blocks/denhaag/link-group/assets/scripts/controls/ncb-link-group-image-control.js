import {useMemo} from "@wordpress/element";
import {MediaPlaceholder} from "@wordpress/block-editor";
import {useSelect} from "@wordpress/data";

/**
 * Returns the Link Group Image Controller.
 *
 * @param {number} imageId The value if the image has caption.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @param {string} className The className of the image.
 * @return {unknown}
 * @constructor
 */
const NCB_LinkGroupImageControl = ({imageId = 0, setAttributes, className = ''}) => {

  const media = useSelect( (select) => select( 'core' ).getMedia( imageId ), [imageId] );

  // On update `value` the controller will be rendered.
  return useMemo( () => {
    if (!imageId || !media) {
      return (
        <MediaPlaceholder
          allowedTypes={['image']}
          multiple={false}
          value={imageId}
          onSelect={(media) => setAttributes( {image: media.id} )}
        />
      );
    }

    // Returns the preferred size, but just in case the thumbnail isn't generated.
    const preferredImageSizeSrc = () => {
      if (!media) {
        return null;
      }
      if (media?.media_details?.sizes?.hasOwnProperty( 'thumbnail' )) {
        return media?.media_details?.sizes?.thumbnail?.source_url;
      }

      return media?.media_details?.sizes?.full?.source_url;
    }

    return (
      <img className={className} src={preferredImageSizeSrc()} alt={media?.alt_text} width="140" height="140"
           loading="lazy" />
    )
  }, [imageId, media] );
};

export default NCB_LinkGroupImageControl;
