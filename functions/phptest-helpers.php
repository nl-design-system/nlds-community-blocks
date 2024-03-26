<?php

// Utility functions for NL Design System Community Blocks
require_once __DIR__ . '/ncb-filter-denhaag-content-links.php';
require_once __DIR__ . '/ncb-str-starts-with.php';
require_once __DIR__ . '/ncb-validate-url.php';
require_once __DIR__ . '/helpers.php';

// Utility functions from copied from WordPress codebase
require_once __DIR__ . '/wp-includes/formatting.php';
require_once __DIR__ . '/wp-includes/functions.php';
require_once __DIR__ . '/wp-includes/http.php';
require_once __DIR__ . '/wp-includes/kses.php';
require_once __DIR__ . '/wp-includes/load.php';
require_once __DIR__ . '/wp-includes/link-template.php';

// Stub functions for often used WordPress functions

/**
 * @see https://developer.wordpress.org/reference/functions/did_action/
 * @param string $hook_name The name of the action hook.
 * @return int The number of times the action hook has been fired.
 */
function did_action( string $hook_name ): int {
    return 0;
}

/**
 * @see https://developer.wordpress.org/reference/functions/apply_filters/
 * @param string $hook_name The name of the filter hook.
 * @param mixed  $value     The value to filter.
 * @param mixed  ...$args   Optional. Additional parameters to pass to the callback functions.
 * @return mixed The filtered value after all hooked functions are applied to it.
 */
function apply_filters( string $hook_name , mixed $value ): mixed {
    return $value;
}

/**
 * @see https://developer.wordpress.org/reference/functions/get_option/
 * @param string $option        Name of the option to retrieve. Expected to not be SQL-escaped.
 * @param mixed  $default_value Optional. Default value to return if the option does not exist.
 * @return mixed Value of the option.
 */
function get_option( string $option , mixed $default_value = false ): mixed {
    return $default_value;
}
