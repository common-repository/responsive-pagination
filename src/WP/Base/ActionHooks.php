<?php
namespace ResponsivePagination\WP\Base;

/**
 * WordPress Action Hooks on main plugin level
 */
class ActionHooks
{
    private static $registered = false;

    public static function register()
    {
        if (!self::$registered) {
            new self();
        }
    }

    protected function __construct()
    {
        // add some hooks if any
    }
}
