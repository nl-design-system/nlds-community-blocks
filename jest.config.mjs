/* eslint-env node */

/** @type {import('ts-jest').JestConfigWithTsJest} */
export default {
	testEnvironment: 'jsdom',
	testPathIgnorePatterns: ['/dist/'],
	verbose: true,
};
