<?php

namespace ResponsivePagination\WP\Base\Front;

use ResponsivePagination\WP\Base\PageDetector;

class Main
{
    public $paginationRegistrar;

    public $apiPaginator;

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
        $this->includes();
        $this->paginationRegistrar = new PaginationRegistrar();
        $this->apiPaginator = new APIPaginator();
        
        if (PageDetector::isFront()) {
            ActionHooks::register();
        }
    }

    protected function includes()
    {
        require_once(respg_path('src/WP/Base/Front/WPQueryExtractor.php'));
        require_once(respg_path('src/WP/Base/Front/PaginationRegistrar.php'));
        require_once(respg_path('src/WP/Base/Front/APIPaginator.php'));
        require_once(respg_path('src/WP/Base/Front/ConversionRegistrator.php'));
        require_once(respg_path('src/WP/Base/Front/PaginationApplicator.php'));
        require_once(respg_path('src/WP/Base/Front/ResponsivenessApplicator.php'));
        require_once(respg_path('src/WP/Base/Front/BreakpointStyleApplicator.php'));
        require_once(respg_path('src/WP/Base/Front/VisualStyleApplicator.php'));
        require_once(respg_path('src/WP/Base/Front/ActionHooks.php'));
    }
}
