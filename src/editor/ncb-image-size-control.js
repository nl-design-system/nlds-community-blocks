import { Dropdown, SelectControl, ToolbarButton } from "@wordpress/components";
import { useMemo } from "@wordpress/element";
import { _x } from "@wordpress/i18n";
import { ReactComponent as icon } from '../blocks/denhaag/image/assets/icons/resolution.svg';

/**
 * Returns the controller to set the image size.
 *
 * @param {string} attribute The attribute key of the image size.
 * @param {boolean} isDisabled Disable the controller if no image is available.
 * @param {object} value The value of the attribute.
 * @param {function} setAttributes The setAttributes function of WordPress.
 * @param {object} media The media object of WordPress.
 * @return {JSX.Element}
 * @constructor
 */
const NCB_ImageSizeControl = ( { attribute = 'size', isDisabled = false, value, setAttributes, media = {} } ) => {
	/**
	 * Capitalize the string.
	 * @param {string} string String to capitalize.
	 * @return {string} Capitalized string.
	 */
	const capitalizeFirstLetter = (string) => {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	return useMemo( () => {
		const options = Object.keys(media).length > 0 ? Object.keys( media?.media_details?.sizes ).map( ( size ) => {
			return {
				label: capitalizeFirstLetter( size ),
				value: size
			}
		}) : [];

		return (
			<Dropdown
				renderToggle={ ( { onToggle } ) => (
					<ToolbarButton
						icon={ icon }
						label={ _x( 'Select image size', 'NCB_ImageSizeControl label', 'nlds-community-blocks' ) }
						onClick={ onToggle }
						isActive={ 'full' !== value }
						disabled={ isDisabled }
					/>
				) }
				renderContent={ () => (
					<SelectControl
						label={ _x( 'Select image size', 'NCB_ImageSizeControl label', 'nlds-community-blocks' ) }
						value={ value }
						options={ options }
						disabled={ isDisabled }
						onChange={  (size) => setAttributes( { [ attribute ]: size } ) }
					/>
				) }
			/>
		);
	}, [ value, isDisabled, media ] );
};
export default NCB_ImageSizeControl;
