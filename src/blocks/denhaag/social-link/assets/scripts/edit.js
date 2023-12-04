import { BlockControls } from '@wordpress/block-editor';
import { useMemo } from '@wordpress/element';
import { _x } from '@wordpress/i18n';
import classNames from 'classnames';
import NCB_LinkControls from '../../../../../editor/ncb-link-control';
import NCB_SetLinkControl from '../../../../../editor/ncb-link-control/assets/scripts/ncb-set-link-control';
import NCB_DenhaagSocialLinkIcon from './components/ncb-denhaag-social-link-icon';

export default function edit({ attributes, setAttributes }) {
	/**
	 * Returns object with classNames.
	 * @type {{root: string, link: string}}
	 * @private
	 */
	const _CLASSES = useMemo(() => {
		return {
			root: classNames('denhaag-socials__item', {}),
			link: 'denhaag-socials__link',
			instruction: 'denhaag-socials__instruction',
		};
	}, [attributes.link]);

	const linkControlOptions = {
		hasRichPreviews: false,
		hasTextControl: false,
		showSuggestions: false,
		createSuggestionButtonText: false,
		settings: false,
	};

	return (
		<>
			<BlockControls>
				<NCB_LinkControls
					value={attributes.link}
					setAttributes={setAttributes}
					options={linkControlOptions}
				/>
			</BlockControls>

			<span className={_CLASSES.link}>
				{(!attributes.hasOwnProperty('link') || !attributes.link) && (
					<>
						<NCB_SetLinkControl
							value={attributes.link}
							setAttributes={setAttributes}
							options={linkControlOptions}
						/>
						<span className={_CLASSES.instruction}>
							{_x(
								'Click the icon to add a link.',
								'ncb-denhaag/social-link: Instruction',
								'nlds-community-blocks'
							)}
						</span>
					</>
				)}
				{attributes.link?.url && (
					<NCB_DenhaagSocialLinkIcon url={attributes.link.url} />
				)}
			</span>
		</>
	);
}
