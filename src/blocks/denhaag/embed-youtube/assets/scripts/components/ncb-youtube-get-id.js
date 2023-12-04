import isUrl from 'is-url';

/**
 * Returns the YouTube Video ID.
 *
 * @param {string} input The input can be a ID or URL.
 * @return {*}
 */
const ncb_youtube_get_id = (input) => {
	if (!isUrl(input)) {
		return input;
	}

	const regex = new RegExp(
		'^(?:http(?:s)?:\\/\\/)?(?:www\\.)?(?:m\\.)?(?:youtu\\.be\\/|youtube\\.com\\/(?:(?:watch)?\\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\\/))([^\\?&\\"\'>]+)',
		'i'
	);
	const ids = regex.exec(input);
	return ids[ids.length - 1];
};

export default ncb_youtube_get_id;
