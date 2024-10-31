<?php

/**
 * Plugin Name: Responsive Pagination
 * Version: 1.4.1
 * Description: Configure your pagination to adapt to different screen size like phones, tablets, desktops, and larger screens.
 * Plugin URI : https://sasikirono.com
 * Author: Bagus Sasikirono
 * Author URI: https://sasikirono.com
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// phpcs:disable

// Entry file
define('RESPG_ENTRYFILE', __FILE__);

// phpcs:enable

// Package Initializer
require_once(dirname(RESPG_ENTRYFILE).'/src/WP/Plugin/PackageInitializer.php');
\ResponsivePagination\WP\Plugin\PackageInitializer::register();
