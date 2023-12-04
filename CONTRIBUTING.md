# Development Rules

-   We follow the WordPress Coding Standards when developing for this plugin, there should be no errors or warnings when using the phpcs.xml.dist ruleset.
-   There can be NO breaking changes, backwards compatibility is important!
-   There should be no dependencies on any paid third party code (plugin and/or package).
-   There should preferably be as least as possible dependencies on any third party code.
    -   If there is a dependency it should preferably be included within the plugin (if necessary via build command).
    -   If there is a dependency and the dependency is missing the code should not break (no fatal errors!), so check for the presence of the dependency and if necessary show an Admin notification.
-   A stable release of the plugin should be installable without the need for any build commands (like `npm run build`)
-   In the first version of this plugin we do not include custom post types and/or taxonomies. Any block that relies on a custom post type and/or taxonomy should be moved to a separate plugin/addon.
-   This plugin is a joined effort between [Acato](https://www.acato.nl) and [Draad](https://www.draad.nl). Additions to this plugin should be approved by both parties.

## Blocks

-   A block should consist of:
    -   `block.json` for all metadata of the block;
    -   `index.js` to register the block;
    -   `class-[block-name].php`, eq to `class-button-group.php` to extend the Base_Block;
    -   `template.php` as the render for the frontend;
    -   `/assets/` directory with `styles` and `scripts` (and other assets like `icons`);
        -   `/scripts/` directory contains:
            -   `edit.js` (required) edit script;
            -   `save.js` (optional), this is based on how the block is saved in WordPress, required if we want Yoast SEO to recognize fields;
            -   `client.js` (optional), Javascript file for the clients. Must be defined in `block.json`;
            -   directories like `controls` for the controls;
            -   directories like `components` for (preview) components and other scripts;
        -   `/styles/` directory contains:
            -   `style.scss`: (optional), styles for the frontend and editor, must be defined in `block.json`.
            -   `editor.scss`: (optional), pure styles for the editor. Must be defined in `block.json`;
