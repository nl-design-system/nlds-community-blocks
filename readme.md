# NL Design System Community Blocks

This plugin adds Gutenberg blocks that implement the NL Design System to the WordPress block editor.

Dynamic block is created and adapted from
https://developer.wordpress.org/block-editor/packages/packages-scripts/

## Installation

### Manual installation

1. Upload the `nlds-community-blocks` folder to the `/wp-content/plugins/` directory.
2. `cd /wp-contents/plugins/nlds-community-blocks`
3. `pnpm install && pnpm run build`
4. Activate the NL Design System Community Blocks plugin through the 'Plugins' menu in WordPress.

### Composer installation

1. `composer config repositories.nlds-community-blocks github https://github.com/nl-design-system/nlds-community-blocks`
2. `composer require nl-design-system/nlds-community-blocks`
3. `cd /wp-contents/plugins/nlds-community-blocks`
4. `pnpm install && pnpm run build`
5. Activate the NL Design System Community Blocks plugin through the 'Plugins' menu in WordPress.

## Coding Standards

Please remember, we use the WordPress PHP Coding Standards for this plugin! (https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/) To check if your changes are compatible with these standards:

-   `cd /wp-content/plugins/nlds-community-blocks`
-   `composer install` (this step is only needed once after installing the plugin)
-   `./vendor/bin/phpcs --standard=phpcs.xml.dist .`
-   See the output if you have made any errors.
    -   Errors marked with `[x]` can be fixed automatically by phpcbf, to do so run: `./vendor/bin/phpcbf --standard=phpcs.xml.dist .`

N.B. the `composer install` command also install a git hook, preventing you from committing code that isn't compatible with the coding standards.

## Translations

```
wp i18n make-pot . languages/nlds-community-blocks.pot --exclude="node_modules/,vendor/" --domain="nlds-community-blocks"
```

```
cd languages && wp i18n make-json nlds-community-blocks-nl_NL.po --no-purge
```

## Building

For the latest building commands check the package.json.

We have a watch command for the WordPress blocks and its assets. Due to the lack of support of the WP Block watcher we're calling the `pnpm run start`, for the assets we run `pnpm run start-assets`.
