/**
 * Small Javascript class for the Denhaag Button animation.
 *
 * @param {string} selector CSS selector to target the button.
 * @param {string} modifier Modifier class to toggle.
 */
const NCB_DenhaagButtonAnimation = (
	selector = 'denhaag-button',
	modifier = 'clicked'
) => {
	const buttons = document.getElementsByClassName(selector);

	if (!buttons || 0 === buttons.length) {
		return;
	}

	const className = `${selector}--${modifier}`;

	Array.from(buttons).forEach((btn) => {
		// Using `addEventListener` instead of `onclick` due to the GravityForms specific functions.
		btn.addEventListener('click', () => btn.classList.add(className));
		btn.addEventListener('mouseleave', () =>
			btn.classList.remove(className)
		);
	});
};

document.addEventListener('DOMContentLoaded', () =>
	NCB_DenhaagButtonAnimation()
);
