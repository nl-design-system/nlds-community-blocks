import { postAuthor } from '@wordpress/icons';
import { ToolbarButton } from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';

/**
 * Return blockquote author control.
 *
 * @param {boolean} value
 * @param {function} setAttributes The setAttributes function of WordPress.
 * @return {JSX.Element}
 * @constructor
 */
const NCB_HasAuthorControl = ({ value = false, setAttributes }) => {
	return useMemo(() => {
		const label = !!value
			? _x(
					'Quote without author',
					'NCB_HasAuthorControl label',
					'nlds-community-blocks'
			  )
			: _x(
					'Quote with author',
					'NCB_HasAuthorControl label',
					'nlds-community-blocks'
			  );
		return (
			<ToolbarButton
				icon={postAuthor}
				label={label}
				onClick={() => setAttributes({ hasAuthor: !value })}
				isActive={!!value}
			/>
		);
	}, [value]);
};

export default NCB_HasAuthorControl;
