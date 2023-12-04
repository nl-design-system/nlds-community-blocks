import { BlockControls, RichText } from '@wordpress/block-editor';
import { ToolbarGroup } from '@wordpress/components';
import { useLayoutEffect, useMemo } from '@wordpress/element';
import { _x } from '@wordpress/i18n';
import classNames from 'classnames';
import NCB_EmbedYouTubeMuteControl from './controls/ncb-embed-youtube-mute-control';
import NCB_EmbedYouTubeLoopControl from './controls/ncb-embed-youtube-loop-control';
import NCB_EmbedYouTubeAutoPlayControls from './controls/ncb-embed-youtube-autoplay-control';
import NCB_EmbedYouTubeControlsControl from './controls/ncb-embed-youtube-controls-control';
import NCB_EmbedYouTubePortraitControls from './controls/ncb-embed-youtube-portrait-control';
import getYouTubeId from './components/ncb-youtube-get-id';
import isUrl from 'is-url';
import { ReactComponent as YouTubeLogo } from '../icons/logo.svg';
import { ReactComponent as Chevron } from '../icons/chevron.svg';

const EMBED_URL = 'https://www.youtube-nocookie.com/embed';
export default function Edit({ attributes, setAttributes }) {
	/**
	 * Return iframe src based on input.
	 * @type {string}
	 */
	const iframeSrc = useMemo(() => {
		if (!attributes.id) {
			return null;
		}

		const paramString = new URLSearchParams({
			autoplay: !!attributes.autoplay ? 1 : 0,
			controls: !!attributes.controls ? 1 : 0,
			loop: !!attributes.loop ? 1 : 0,
			mute: !!attributes.mute ? 1 : 0,
			modestbranding: 0,
			showinfo: 0,
			disablekb: 1,
		}).toString();

		return `${EMBED_URL}/${getYouTubeId(attributes.id)}?${paramString}`;
	}, [
		attributes.id,
		attributes.autoplay,
		attributes.controls,
		attributes.loop,
		attributes.mute,
		attributes.portrait,
	]);

	// Set URL to video ID.
	useLayoutEffect(() => {
		if (!!attributes.id && !!isUrl(attributes.id)) {
			setAttributes({ id: getYouTubeId(attributes.id) });
		}
	}, [attributes.id]);

	const _CLASSES = useMemo(() => {
		return {
			root: classNames('denhaag-embed-youtube', {
				['denhaag-embed-youtube--no-video']: !attributes.id,
				['denhaag-embed-youtube--portrait']: !!attributes.portrait,
			}),
			input: 'denhaag-embed-youtube__input',
			details: 'denhaag-embed-youtube__details',
			summary: 'denhaag-embed-youtube__summary',
			content: 'denhaag-embed-youtube__content',
		};
	}, [attributes.id, attributes.portrait]);

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<NCB_EmbedYouTubeAutoPlayControls
						value={!!attributes.autoplay}
						isDisabled={!attributes.id}
						setAttributes={setAttributes}
					/>
					<NCB_EmbedYouTubeLoopControl
						value={!!attributes.loop}
						isDisabled={!attributes.id}
						setAttributes={setAttributes}
					/>
					<NCB_EmbedYouTubeControlsControl
						value={!!attributes.controls}
						isDisabled={!attributes.id}
						setAttributes={setAttributes}
					/>
					<NCB_EmbedYouTubeMuteControl
						value={!!attributes.mute}
						isDisabled={!attributes.id}
						setAttributes={setAttributes}
					/>
					<NCB_EmbedYouTubePortraitControls
						value={!!attributes.portrait}
						isDisabled={!attributes.id}
						setAttributes={setAttributes}
					/>
				</ToolbarGroup>
			</BlockControls>

			{!attributes.id && (
				<div className={_CLASSES.root}>
					<YouTubeLogo />
					<div>
						<strong>
							{_x(
								'YouTube video URL or ID:',
								'ncb-denhaag/embed-youtube: Title',
								'ncb-community-blocks'
							)}
						</strong>
						<RichText
							tagName="div"
							className={_CLASSES.input}
							withoutInteractiveFormatting
							placeholder={_x(
								'YouTube video URL or ID…',
								'ncb-denhaag/embed-youtube: Title',
								'ncb-community-blocks'
							)}
							value={attributes.id}
							onChange={(id) =>
								setAttributes({ id: getYouTubeId(id) })
							}
							allowedFormats={[]}
							disableLineBreaks={true}
						/>
					</div>
					<div>
						<strong>
							{_x(
								'ScreenReader caption:',
								'denhaag/embed-youtube: Title',
								'ncb-community-blocks'
							)}
						</strong>
						<RichText
							tagName="div"
							className={_CLASSES.input}
							withoutInteractiveFormatting
							placeholder={_x(
								'YouTube video ScreenReader caption…',
								'denhaag/embed-youtube: Placeholder',
								'ncb-community-blocks'
							)}
							value={attributes.title}
							onChange={(description) =>
								setAttributes({ description })
							}
							allowedFormats={[]}
							disableLineBreaks={true}
						/>
					</div>
				</div>
			)}
			{!!attributes.id && iframeSrc && (
				<>
					<iframe
						className={_CLASSES.root}
						frameBorder="0"
						title="video-embed"
						src={iframeSrc}
					/>
					<details className={_CLASSES.details}>
						<summary className={_CLASSES.summary}>
							{_x(
								'Option',
								'denhaag/embed-youtube: Summary',
								'ncb-community-blocks'
							)}
							<Chevron />
						</summary>
						<div className={_CLASSES.content}>
							<div>
								<strong>
									{_x(
										'YouTube video URL or ID:',
										'denhaag/embed-youtube: Title',
										'ncb-community-blocks'
									)}
								</strong>
								<RichText
									tagName="div"
									className={_CLASSES.input}
									withoutInteractiveFormatting
									placeholder={_x(
										'YouTube video URL or ID…',
										'denhaag/embed-youtube: Title',
										'ncb-community-blocks'
									)}
									value={attributes.id}
									onChange={(id) =>
										setAttributes({ id: getYouTubeId(id) })
									}
									allowedFormats={[]}
									disableLineBreaks={true}
								/>
							</div>
							<div>
								<strong>
									{_x(
										'ScreenReader caption:',
										'denhaag/embed-youtube: Title',
										'ncb-community-blocks'
									)}
								</strong>
								<RichText
									tagName="div"
									className={_CLASSES.input}
									withoutInteractiveFormatting
									placeholder={_x(
										'YouTube video ScreenReader caption…',
										'denhaag/embed-youtube: Placeholder',
										'ncb-community-blocks'
									)}
									value={attributes.description}
									onChange={(description) =>
										setAttributes({ description })
									}
									allowedFormats={[]}
									disableLineBreaks={true}
								/>
							</div>
						</div>
					</details>
				</>
			)}
		</>
	);
}
