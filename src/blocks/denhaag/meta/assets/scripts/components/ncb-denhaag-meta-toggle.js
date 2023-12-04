/**
 * Small Javascript class for the Denhaag Button animation.
 *
 * @param {string} id ID of the button.
 */
const NCB_DenhaagMetaToggle = (id = 'denhaag-share-button') => {
	const button = document.getElementById(id);

	if (!button) {
		return;
	}

	button.addEventListener('click', () =>
		button.setAttribute(
			'aria-expanded',
			'false' === button.getAttribute('aria-expanded')
		)
	);
	button.addEventListener('keydown', (e) =>
		NCB_DenhaagMetaToggleWrapper(button, e)
	);

	const wrapper = document.getElementById(
		button.getAttribute('aria-controls')
	);
	if (!!wrapper) {
		wrapper.addEventListener('keydown', (e) =>
			NCB_DenhaagMetaToggleWrapper(button, e)
		);
	}
};

/**
 * Set attribute to element.
 *
 * @param {HTMLObjectElement} el
 * @param {object} event Event object.
 * @return {boolean}
 * @constructor
 */
const NCB_DenhaagMetaToggleWrapper = (el, event) => {
	if (!el) {
		return false;
	}
	if (event.key === 'Escape') {
		el.setAttribute('aria-expanded', 'false');
		return true;
	}

	return false;
};

export default NCB_DenhaagMetaToggle;
