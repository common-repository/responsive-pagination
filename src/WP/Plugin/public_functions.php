<?php
/**
 * Public functions related to responsive-pagination plugin
 */

/**
 * Returning our main plugin
 *
 * @return Main
 */
function respg()
{
    return \ResponsivePagination\WP\Plugin\Main::instance();
}
