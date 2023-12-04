import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as icon } from './assets/icons/block-icon.svg';
import edit from './assets/scripts/edit';
import save from './assets/scripts/save';
import metadata from './block.json';

const { name } = metadata;

registerBlockType(name, {
	...metadata,
	icon,
	edit,
	save,
});
