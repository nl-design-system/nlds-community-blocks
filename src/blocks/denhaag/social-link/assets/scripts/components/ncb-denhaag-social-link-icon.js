import { _x } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { ReactComponent as Facebook } from '../../icons/facebook.svg';
import { ReactComponent as Twitter } from '../../icons/twitter.svg';
import { ReactComponent as Instagram } from '../../icons/instagram.svg';
import { ReactComponent as LinkedIn } from '../../icons/linkedin.svg';
import { ReactComponent as YouTube } from '../../icons/youtube.svg';
import { ReactComponent as WhatsApp } from '../../icons/whatsapp.svg';

/**
 * Returns the Social media logo.
 *
 * @param {string} url The url to check.
 * @return {unknown}
 * @constructor
 */
const NCB_DenhaagSocialLinkIcon = ({ url }) => {
	// On update `value` the controller will be rendered.

	return useMemo(() => {
		if (!url) {
			return _x(
				'No link provided',
				'denhaag/social-link icon: Notice',
				'nlds-community-blocks'
			);
		}

		if (url.includes('facebook.com')) {
			return <Facebook />;
		}
		if (url.includes('twitter.com')) {
			return <Twitter />;
		}
		if (url.includes('instagram.com')) {
			return <Instagram />;
		}
		if (url.includes('linkedin.com')) {
			return <LinkedIn />;
		}
		if (
			url.includes('youtube.com') ||
			url.includes('youtu.be') ||
			url.includes('youtube-nocookie.com')
		) {
			return <YouTube />;
		}
		if (url.includes('https://wa.me/')) {
			return <WhatsApp />;
		}

		return _x(
			'This is an unsupported domain.',
			'denhaag/social-link icon: Notice',
			'nlds-community-blocks'
		);
	}, [url]);
};

export default NCB_DenhaagSocialLinkIcon;
