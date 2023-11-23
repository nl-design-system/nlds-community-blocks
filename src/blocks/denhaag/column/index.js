import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import edit from './assets/scripts/edit';
import metadata from './block.json';
import {ReactComponent as icon} from './assets/icons/block-icon.svg';

const { name } = metadata;

registerBlockType(name, {
  ...metadata,
  icon,
  edit,
  save: () => <InnerBlocks.Content />,
});
