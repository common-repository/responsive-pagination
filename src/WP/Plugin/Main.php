<?php

namespace ResponsivePagination\WP\Plugin;

use ResponsivePagination\WP\Base\Main as BaseMain;
use ResponsivePagination\WP\Base\ProxyPaginationSettings;
use ResponsivePagination\WP\Base\ProxyStoredConvertedElements;

/**
 * Plugin Main Class
 */
class Main extends BaseMain
{
    public $version = '1.4.1';

    public $packageName = 'responsive-pagination';

    public $packageType = 'plugin';

    public $assetsNamespace = 'base';

    private static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        parent::__construct();
        ActionHooks::register();
    }

    protected function includes()
    {
        parent::includes();
        require_once(respg_path('src/WP/Plugin/ActionHooks.php'));
    }
}
