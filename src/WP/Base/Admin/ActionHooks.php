<?php

namespace ResponsivePagination\WP\Base\Admin;

use ResponsivePagination\WP\Base\PageDetector;

/**
 * WordPress Action Hooks in Admin side.
 * All hooks in this class operates after 'wp' hooks
 */
class ActionHooks
{
    /**
     * Whether this Action Hooks already registered or not
     *
     * @var boolean
     */
    private static $registered = false;

    public static function register()
    {
        if (!self::$registered) {
            new self();
        }
        self::$registered = true;
    }

    private function __construct()
    {
        add_action('wp_loaded', function () {
            $this->adminEnqueueScripts();
            $this->adminHead();
        });
    }

    protected function adminHead()
    {
        add_action('admin_head', function () {
            $storedConvertedElements = respg()->proxyStoredConvertedElements->retrieve();
            $registeredPaginationSettings = respg()->proxyPaginationSettings->retrieve();

            printf(
                '<script>
                    window._respg = {};
                    window._respg.version = "%s";
                    window._respg.initialValue = {};
                    window._respg.initialValue.paginationSettings = %s;
                    window._respg.initialValue.breakpoint = %s;
                    window._respg.storedConvertedElements = %s;
                    window._respg.registeredPaginationSettings = %s;
                </script>',
                respg()->version,
                json_encode(respg()->proxyPaginationSettings->initialValue()),
                json_encode(respg()->proxyPaginationSettings->initialBreakpointValue()),
                json_encode($storedConvertedElements),
                json_encode($registeredPaginationSettings)
            );
        });
    }

    protected function adminEnqueueScripts()
    {
        add_action('admin_enqueue_scripts', function () {
            // React
            $reactUrl = respg()->isPublic?
                respg_url('assets/js/react.production.min.js') :
                respg_url(sprintf('assets/%s/js/react.development.js', respg()->assetsNamespace));
            wp_enqueue_script('react-16.13', $reactUrl, array(), '16.13.1');
            
            // React DOM
            $reactDomUrl = respg()->isPublic?
                respg_url('assets/js/react-dom.production.min.js') :
                respg_url(sprintf('assets/%s/js/react-dom.development.js', respg()->assetsNamespace));
            wp_enqueue_script('react-dom-16.13', $reactDomUrl, array(), '16.13.1');

            // Our scripts & styles
            $tabs = respg()->admin->getAdminTabs();
            $currentTabName = PageDetector::getAdminTabName();
            $currentTab;
            foreach ($tabs as $tab) {
                if ($tab['slug'] === $currentTabName) {
                    $currentTab = $tab;
                    break;
                }
            }
            
            wp_enqueue_script(
                sprintf('respg-%s-script', $currentTabName),
                $currentTab['scriptUrl'],
                ['react-16.13', 'react-dom-16.13'], // dependencies
                respg()->isPublic? respg()->version : uniqid(),
                true
            );

            if (respg()->isPublic) {
                wp_enqueue_style(
                    sprintf('respg-%s-style', $currentTabName),
                    $currentTab['styleUrl'],
                    array(),
                    respg()->version
                );
            }
        });
    }
}
