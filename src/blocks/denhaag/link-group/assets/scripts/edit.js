import {_x} from '@wordpress/i18n';
import {
  BlockControls,
  InnerBlocks,
  RichText,
} from '@wordpress/block-editor';
import {useLayoutEffect, useMemo} from '@wordpress/element';
import classNames from 'classnames';
import NCB_HeadingTagControl from '../../../../../editor/ncb-heading-tag-control';
import NCB_LinkGroupImageControl from "./controls/ncb-link-group-image-control";
import NCB_LinkGroupImageResetControl from "./controls/ncb-link-group-image-reset-control";

export default function edit({attributes, setAttributes}) {
  const {is_editor} = window?.ncb_editor_variables?.denhaag?.['link-group'];
  const _CLASSES = useMemo( () => {
    return {
      root: classNames( 'denhaag-link-group', {
        ['denhaag-link-group--dark']: !is_editor
      } ),
      caption: classNames( 'denhaag-link-group__caption', {
        [`utrecht-heading-${attributes.appearance}`]: !!attributes.appearance,
      } ),
      image: 'denhaag-link-group__image',
    };
  }, [
    attributes.appearance,
    is_editor
  ] );

  useLayoutEffect( () => {
    setAttributes( {
      isDark: !is_editor
    } );

    // Reset image variable.
    if (!!is_editor && !!attributes.image) {
      setAttributes( {image: 0} );
    }
  }, [is_editor] );

  return (
    <>
      <BlockControls>
        <NCB_HeadingTagControl
          value={attributes.level}
          allowedTags={attributes.allowedLevels}
          setAttributes={setAttributes}
        />
        <NCB_HeadingTagControl
          value={attributes.appearance}
          attribute="appearance"
          allowedTags={attributes.allowedLevels}
          setAttributes={setAttributes}
        />
        {!!is_editor && (
          <NCB_LinkGroupImageResetControl
            isDisabled={!attributes.image}
            setAttributes={setAttributes}
          />
        )}
      </BlockControls>

      <div className={_CLASSES.root}>
        {!!is_editor && (
          <NCB_LinkGroupImageControl
            imageId={attributes.image}
            className={_CLASSES.image}
            setAttributes={setAttributes}
          />
        )}
        <RichText
          tagName={`h${attributes.level}`}
          className={_CLASSES.caption}
          value={attributes.caption}
          placeholder={_x(
            'Place your caption here',
            'ncb-denhaag/link-group-item: Caption placeholder',
            'nlds-community-blocks'
          )}
          onChange={(caption) => setAttributes( {caption} )}
          allowedFormats={[]}
        />
        <ul className="utrecht-link-list utrecht-link-list--html-ul denhaag-link-group__list">
          <InnerBlocks
            allowedBlocks={['ncb-denhaag/link-group-item']}
            template={[['ncb-denhaag/link-group-item', {}]]}
            templateLock={false}
          />
        </ul>
      </div>
    </>
  );
}
