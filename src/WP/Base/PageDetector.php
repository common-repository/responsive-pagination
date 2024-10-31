<?php

namespace ResponsivePagination\WP\Base;

class PageDetector
{
    public static function isFront()
    {
        return !is_admin() && $GLOBALS['pagenow'] !== 'wp-login.php' ;
    }

    public static function isAdmin()
    {
        return is_admin() && $GLOBALS['pagenow'] !== 'wp-login.php';
    }

    public static function isOurAdmin()
    {
        return self::isAdmin() && isset($_GET['page']) && $_GET['page'] === 'responsive-pagination.php';
    }

    /**
     * Whether our script is running on current page or not.
     *
     * @return boolean
     */
    public static function isOurPage()
    {
        return self::isOurAdmin() || self::isFront();
    }

    public static function getAdminTabName()
    {
        $defaultTab = 'convert-paginations';

        if (!self::isOurAdmin()) {
            return false;
        }
        
        if (!isset($_GET['tab'])) {
            return $defaultTab;
        }

        $currentTab = $_GET['tab'];
        $slugAdminTabs = respg()->admin->getAdminTabs(true);

        return in_array($currentTab, $slugAdminTabs) ? $currentTab : $defaultTab;
    }
}
