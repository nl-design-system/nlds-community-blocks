import classNames from 'classnames';
import {useMemo, useState} from '@wordpress/element';
import {BlockControls} from '@wordpress/block-editor';
import NCB_MetaExcerptControl from './controls/ncb-meta-excerpt-control';
import NCB_DenhaagMetaSharePreview from "./components/ncb-denhaag-meta-share-preview";
import NCB_DenhaagMetaMetaPreview from "./components/ncb-denhaag-meta-meta-preview";

const Edit = ({attributes, setAttributes}) => {
  // Store temporary in useState so we get realtime preview.
  const [title, setTitle] = useState( wp.data.select( 'core/editor' ).getEditedPostAttribute( 'title' ) );
  const [excerpt, setExcerpt] = useState( wp.data.select( 'core/editor' ).getEditedPostAttribute( 'excerpt' ) );

  const {supported} = window.ncb_editor_variables?.denhaag['meta'];

  /*
   * Update data on key change of specific postAttribute.
   */
  wp.data.subscribe( function () {
    const newTitle = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'title' );
    setTitle( newTitle );

    const newExcerpt = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'excerpt' );
    setExcerpt( newExcerpt );
  } );

  const _CLASSES = {
    root: `denhaag-meta`,
    title: classNames( 'page-title', 'utrecht-heading-1' ),
    excerpt: classNames( 'utrecht-paragraph', 'utrecht-paragraph--lead' )
  };

  if (!attributes.share && !attributes.meta) {
    return (<>
      <BlockControls>
        <NCB_MetaExcerptControl
          value={attributes.excerpt}
          isDisabled={!excerpt}
          setAttributes={setAttributes}
        />
      </BlockControls>

      {!! supported.title && !!title && <h1 className={_CLASSES.title}>{title}</h1>}
      {!! supported.excerpt && !!excerpt && !!attributes.excerpt && <p className={_CLASSES.excerpt}>{excerpt}</p>}
    </>);
  }

  return (<>
    <BlockControls>
      <NCB_MetaExcerptControl
        value={attributes.excerpt}
        isDisabled={!excerpt}
        setAttributes={setAttributes}
      />
    </BlockControls>

    <div className={_CLASSES.root}>
      {!!supported.title && !!title && <h1 className={_CLASSES.title}>{title}</h1>}
      {!!attributes.meta && <NCB_DenhaagMetaMetaPreview showMeta={attributes.meta} />}
      {!!attributes.share && <NCB_DenhaagMetaSharePreview />}
      {!!supported.excerpt && !!excerpt && !!attributes.excerpt && <p className={_CLASSES.excerpt}>{excerpt}</p>}
    </div>
  </>);
};

export default Edit;
