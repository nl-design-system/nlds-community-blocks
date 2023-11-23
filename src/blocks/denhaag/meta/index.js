import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as icon } from './assets/icons/heading.svg';
import edit from './assets/scripts/edit';
import metadata from './block.json';

const { name } = metadata;

registerBlockType(name, {
	...metadata,
	icon,
	edit,
	save: () => null,
});
