# WordPress dependencies

WordPress has no convenient dependency with utility functions that our components need, specifically during unit testing.

For this reason, we have made a hardcoded copy of the files we depend on. When you upgrade WordPress, it is best to copy the latest matching version of those dependencies.

The exact versions we copied are listed in `download.sh`.

Browse the latest version of `wp-includes/` on GitHub: [`src/wp-includes/`](https://github.com/WordPress/wordpress-develop/blob/trunk/src/wp-includes/)
