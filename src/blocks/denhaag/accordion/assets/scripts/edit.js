import {BlockControls, InnerBlocks} from '@wordpress/block-editor';
import {useLayoutEffect} from "@wordpress/element";
import {useSelect} from "@wordpress/data";
import NCB_HeadingTagControl from "../../../../../editor/ncb-heading-tag-control";

export default function edit({attributes, setAttributes, clientId}) {
  /**
   * Returns the amount of innerBlocks.
   * @type {import("../../types").UseSelectReturn<function(*): *>}
   * @return {int}
   */
  const innerBlocksLength = useSelect( (select) => select( 'core/block-editor' ).getBlocks( clientId ).length, [clientId] );

  useLayoutEffect( () => {
    if (0 === innerBlocksLength) {
      // Forcefully appends a new block when deleting the last innerBlock.
      wp.data.dispatch( 'core/block-editor' ).insertBlocks( wp.blocks.createBlock( 'ncb-denhaag/accordion-item', {} ), 0, clientId );
    }
  }, [innerBlocksLength] );

  return (
    <>
      <BlockControls>
        <NCB_HeadingTagControl
          value={attributes.tag}
          allowedTags={attributes.allowedTags}
          setAttributes={setAttributes}
        />
      </BlockControls>
      <div className="denhaag-accordion">
        <InnerBlocks
          allowedBlocks={['ncb-denhaag/accordion-item']}
          template={[['ncb-denhaag/accordion-item', {heading: attributes.tag}]]}
          templateLock={false}
          renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
        />
      </div>
    </>
  );
}
