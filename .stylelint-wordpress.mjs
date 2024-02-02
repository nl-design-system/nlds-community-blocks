/** @type {import('stylelint').Config} */
import wordpressConfig from '@wordpress/stylelint-config';

/**
 * WordPress @wordpress/stylelint-config@21.33.0 is designer for stylelint@14, but we want use stylelint@16
 * Many code formatting related rules were removed from stylelint 15, we need to remove those from the config.
 * @see https://stylelint.io/migration-guide/to-15/
 */

const deprecatedRules = [
	'at-rule-name-case',
	'at-rule-name-newline-after',
	'at-rule-name-space-after',
	'at-rule-semicolon-newline-after',
	'at-rule-semicolon-space-before',
	'block-closing-brace-empty-line-before',
	'block-closing-brace-newline-after',
	'block-closing-brace-newline-before',
	'block-closing-brace-space-after',
	'block-closing-brace-space-before',
	'block-opening-brace-newline-after',
	'block-opening-brace-newline-before',
	'block-opening-brace-space-after',
	'block-opening-brace-space-before',
	'color-hex-case',
	'declaration-bang-space-after',
	'declaration-bang-space-before',
	'declaration-block-semicolon-newline-after',
	'declaration-block-semicolon-newline-before',
	'declaration-block-semicolon-space-after',
	'declaration-block-semicolon-space-before',
	'declaration-block-trailing-semicolon',
	'declaration-colon-newline-after',
	'declaration-colon-space-after',
	'declaration-colon-space-before',
	'function-comma-newline-after',
	'function-comma-newline-before',
	'function-comma-space-after',
	'function-comma-space-before',
	'function-max-empty-lines',
	'function-parentheses-newline-inside',
	'function-parentheses-space-inside',
	'function-whitespace-after',
	'indentation',
	'linebreaks',
	'max-empty-lines',
	'max-line-length',
	'media-feature-colon-space-after',
	'media-feature-colon-space-before',
	'media-feature-name-case',
	'media-feature-parentheses-space-inside',
	'media-feature-range-operator-space-after',
	'media-feature-range-operator-space-before',
	'media-query-list-comma-newline-after',
	'media-query-list-comma-newline-before',
	'media-query-list-comma-space-after',
	'media-query-list-comma-space-before',
	'no-empty-first-line',
	'no-eol-whitespace',
	'no-extra-semicolons',
	'no-missing-end-of-source-newline',
	'number-leading-zero',
	'number-no-trailing-zeros',
	'property-case',
	'selector-attribute-brackets-space-inside',
	'selector-attribute-operator-space-after',
	'selector-attribute-operator-space-before',
	'selector-combinator-space-after',
	'selector-combinator-space-before',
	'selector-descendant-combinator-no-non-space',
	'selector-list-comma-newline-after',
	'selector-list-comma-newline-before',
	'selector-list-comma-space-after',
	'selector-list-comma-space-before',
	'selector-max-empty-lines',
	'selector-pseudo-class-case',
	'selector-pseudo-class-parentheses-space-inside',
	'selector-pseudo-element-case',
	'string-quotes',
	'unicode-bom',
	'unit-case',
	'value-list-comma-newline-after',
	'value-list-comma-newline-before',
	'value-list-comma-space-after',
	'value-list-comma-space-before',
	'value-list-max-empty-lines',
];

const modernConfig = {
	// ...wordpressConfig,
	rules: Object.fromEntries(
		Object.entries(wordpressConfig.rules).filter(
			([key, value]) => !deprecatedRules.includes(key)
		)
	),
};

export default modernConfig;
