<?php

namespace ResponsivePagination\WP\Base;

use ResponsivePagination\WP\Base\ProxyPaginationSettings;
use ResponsivePagination\WP\Base\ProxyStoredConvertedElements;
use ResponsivePagination\WP\Base\Admin\Main as MainAdmin;
use ResponsivePagination\WP\Base\Front\Main as MainFront;

/**
 * Plugin Main Class
 */
class Main
{
    public $admin;

    public $front;

    public $proxyPaginationSettings;

    public $proxyStoredConvertedElements;

    public $isPublic;

    private static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        $this->checkPluginStatus();
        $this->includes();
        $this->proxyPaginationSettings = new ProxyPaginationSettings();
        $this->proxyStoredConvertedElements = new ProxyStoredConvertedElements();
        $this->admin = MainAdmin::instance();
        $this->front = MainFront::instance();
    }

    protected function includes()
    {
        require_once(respg_path('src/Helper.php'));
        require_once(respg_path('src/WP/Base/PageDetector.php'));
        require_once(respg_path('src/WP/Base/ActionHooks.php'));
        require_once(respg_path('src/WP/Base/ProxyPaginationSettings.php'));
        require_once(respg_path('src/WP/Base/ProxyStoredConvertedElements.php'));
        require_once(respg_path('src/WP/Base/public_api.php'));
        require_once(respg_path('src/WP/Base/Admin/Main.php'));
        require_once(respg_path('src/WP/Base/Front/Main.php'));
    }


    /**
     * Check plugin status, and set is_public property.
     *
     * @return void
     */
    private function checkPluginStatus()
    {
        $path           = respg_path('development.json');
        $this->isPublic = ! file_exists($path);
    }
}
