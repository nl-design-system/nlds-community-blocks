=== NL Design System Community Blocks ===
Contributors: acato, rockfire
Tags: nl design system, nlds, gutenberg, blocks
Requires at least: 5.9
Tested up to: 6.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: EUPL-1.2
License URI: https://eupl.eu/1.2/en

Adds NL Design System Community Blocks to the Gutenberg editor.

== Description ==

This plugin adds Gutenberg blocks that implement the NL Design System to the WordPress block editor.

== Installation ==

=== Manual installation ===

1. Upload the `nlds-community-blocks` folder to the `/wp-content/plugins/` directory.
2. `cd /wp-contents/plugins/nlds-community-blocks`
3. `pnpm install && pnpm run build`
4. Activate the NL Design System Community Gutenberg Blocks plugin through the 'Plugins' menu in WordPress.

=== Composer installation ===
1. `composer config repositories.nlds-community-blocks github https://github.com/nl-design-system/nlds-community-blocks`
2. `composer require nl-design-system/nlds-community-blocks`
3. `pnpm install && pnpm run build`
4. Activate the NL Design System Community Gutenberg Blocks plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 1.0.0 =

First working version.
