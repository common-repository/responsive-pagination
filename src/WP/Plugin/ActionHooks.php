<?php
namespace ResponsivePagination\WP\Plugin;

use ResponsivePagination\WP\Base\ActionHooks as BaseActionHooks;
use ResponsivePagination\WP\Base\Admin\Main as MainAdmin;
use ResponsivePagination\WP\Base\Front\Main as MainFront;

/**
 * WordPress Action Hooks on main plugin level
 */
class ActionHooks extends BaseActionHooks
{
    private static $registered = false;

    public static function register()
    {
        if (!self::$registered) {
            new self();
        }
    }

    private function __construct()
    {
        parent::__construct();
        // add some hooks for this plugin if needed
    }
}
