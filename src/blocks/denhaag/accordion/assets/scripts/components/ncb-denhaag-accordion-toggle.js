/**
 * Small Javascript function for the Denhaag Accordion to work.
 * @param {string} selector The CSS selector for the accordion item.
 * @constructor
 */
const NCB_DenhaagAccordionToggle = (
	selector = 'denhaag-accordion__container'
) => {
	[...document.getElementsByClassName(selector)]?.forEach((element) => {
		const toggle = element.querySelector('[aria-controls]');

		toggle.onclick = () => {
			element.classList.toggle(`${selector}--open`);
			toggle.setAttribute(
				'aria-expanded',
				'true' !== toggle.getAttribute('aria-expanded')
					? 'true'
					: 'false'
			);
		};
	});
};

export default NCB_DenhaagAccordionToggle;
