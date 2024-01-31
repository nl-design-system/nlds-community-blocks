/* eslint-env node */

/**
 * I couldn't refactor this to `.mjs` yet, because I got the following error when running `jest`:
 *
 *         You appear to be using a native ECMAScript module configuration file, which is only supported when running Babel asynchronously.
 */
module.exports = {
	presets: ['@babel/preset-env', '@babel/preset-react'],
};
