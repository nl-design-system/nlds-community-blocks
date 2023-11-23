import {registerBlockType} from '@wordpress/blocks';
import edit from './assets/scripts/edit';
import metadata from './block.json';

const {name} = metadata;

registerBlockType( name, {
  ...metadata,
  edit,
  save: () => null,
} );
