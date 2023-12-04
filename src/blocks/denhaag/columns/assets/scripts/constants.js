import { ReactComponent as iconFiftyFifty } from '../icons/column-fifty-fifty.svg';
import { ReactComponent as iconOneThirdTwoThird } from '../icons/column-one-third-two-third.svg';
import { ReactComponent as iconTwoThirdOneThird } from '../icons/column-two-third-one-third.svg';
import { ReactComponent as iconOneThirdOneThirdOneThird } from '../icons/column-one-third-one-third-one-third.svg';
import { ReactComponent as iconSingle } from '../icons/column-single.svg';
export const _ALLOWED_INNERBLOCKS = ['ncb-denhaag/column'];
export const _VARIATIONS = [
	{
		name: 'single',
		label: '100%',
		icon: iconSingle,
		columns: 1,
	},
	{
		name: 'fifty-fifty',
		label: '50% / 50%',
		icon: iconFiftyFifty,
		columns: 2,
	},
	{
		name: 'one-third-two-third',
		label: '33% / 66%',
		icon: iconOneThirdTwoThird,
		columns: 2,
	},
	{
		name: 'two-third-one-third',
		label: '66% / 33%',
		icon: iconTwoThirdOneThird,
		columns: 2,
	},
	{
		name: 'one-third-one-third-one-third',
		label: '33% / 33% / 33%',
		icon: iconOneThirdOneThirdOneThird,
		columns: 3,
	},
];
