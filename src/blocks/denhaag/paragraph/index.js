import { registerBlockType } from '@wordpress/blocks';
import edit from './assets/scripts/edit';
import save from './assets/scripts/save';
import metadata from './block.json';

const { name } = metadata;

registerBlockType( name, {
	...metadata,
	edit,
	save,
} );
