<?php

namespace ResponsivePagination\WP\Plugin;

class PackageInitializer
{
    private static $registered;

    public static function register()
    {
        if (!isset(self::$registered)) {
            // register package
            self::registerPackage();

            // register initializer
            self::registerInitializer();

            self::$registered = true;
        }
    }

    private function registerPackage()
    {
        add_filter('respg_installed_packages', function ($installedPackages) {
            array_push($installedPackages, array(
                'packageName' => 'responsive-pagination',
                'priority' => 20
            ));
            return $installedPackages;
        });
    }

    private function registerInitializer()
    {
        add_action('init', function () {
            $installedPackages = apply_filters('respg_installed_packages', []);
            $prioritizedPackage = false;

            foreach ($installedPackages as $package) {
                if (!$prioritizedPackage) {
                    $prioritizedPackage = $package;
                } elseif ($package['priority'] < $prioritizedPackage['priority']) {
                    $prioritizedPackage = $package;
                }
            }

            if ($prioritizedPackage['packageName'] === 'responsive-pagination') {
                // requiring
                require_once(dirname(RESPG_ENTRYFILE).'/src/WP/Base/Main.php');
                require_once(dirname(RESPG_ENTRYFILE).'/src/WP/Base/public_functions.php');
                require_once(dirname(RESPG_ENTRYFILE).'/src/WP/Plugin/Main.php');
                require_once(dirname(RESPG_ENTRYFILE).'/src/WP/Plugin/public_functions.php');

                // initialize
                respg();
            }
        });
    }
}
