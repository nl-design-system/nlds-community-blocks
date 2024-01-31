import { render } from '@testing-library/react';
import SaveParagraph from './save';
import '@testing-library/jest-dom';
import React from 'react';

describe('Saved paragraph', () => {
	it('renders an HTML p element', () => {
		const { container } = render(<SaveParagraph />);

		const paragraph = container.querySelector('p:only-child');

		expect(paragraph).toBeInTheDocument();
	});
});
