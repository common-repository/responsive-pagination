<?php

namespace ResponsivePagination\WP\Base\Admin;

use ResponsivePagination\WP\Base\PageDetector;

class Main
{
    public $saveNotification;

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
        
        // register ajax handler
        AjaxHandler::register();

        // this also creates page responsive-pagination.php
        $this->initializeAdminPage();

        // only happen for our page
        if (PageDetector::isOurAdmin()) {
            ActionHooks::register();
        }
    }

    /**
     * Get all admin tabs data.
     * Definition of main tabs is also here.
     *
     * @return array $adminTabs
     */
    public function getAdminTabs($onlySlugs = false)
    {
        $adminTabs = apply_filters('respg_admin_tabs', [
            array(
                'slug' => 'convert-paginations',
                'title' => 'Convert Paginations',
                'scriptUrl' => respg()->isPublic ?
                    respg_url('assets/js/convertPaginations.wp.respg.min.js') :
                    respg_url(sprintf('assets/%s/js/convertPaginations.wp.respg.js', respg()->assetsNamespace)),
                'styleUrl' => respg_url('assets/css/convertPaginations.min.css')
            ),
            array(
                'slug' => 'pagination-settings',
                'title' => 'Pagination Settings',
                'scriptUrl' => respg()->isPublic ?
                    respg_url('assets/js/paginationSettings.wp.respg.min.js') :
                    respg_url(sprintf('assets/%s/js/paginationSettings.wp.respg.js', respg()->assetsNamespace)),
                'styleUrl' => respg_url('assets/css/paginationSettings.min.css')
            ),
            array(
                'slug' => 'breakpoint-configurations',
                'title' => 'Breakpoint Configurations',
                'scriptUrl' => respg()->isPublic ?
                    respg_url('assets/js/breakpointConfigurations.wp.respg.min.js') :
                    respg_url(sprintf('assets/%s/js/breakpointConfigurations.wp.respg.js', respg()->assetsNamespace)),
                'styleUrl' => respg_url('assets/css/breakpointConfigurations.min.css')
            ),
        ]);

        if ($onlySlugs) {
            return array_map(function ($tabItem) {
                return $tabItem['slug'];
            }, $adminTabs);
        } else {
            return $adminTabs;
        }
    }

    protected function initializeAdminPage()
    {
        add_action('admin_menu', function () {
            $adminTabs = $this->getAdminTabs();

            new AdminPage(
                'Responsive Pagination',
                'responsive-pagination.php',
                'Responsive Pagination Settings',
                'Responsive Pagination',
                $adminTabs,
                'manage_options'
            );
        });
    }

    protected function includes()
    {
        require_once(respg_path('src/WP/Base/Admin/AjaxHandler.php'));
        require_once(respg_path('src/WP/Base/Admin/ActionHooks.php'));
        require_once(respg_path('src/WP/Base/Admin/AdminPage.php'));
    }
}
