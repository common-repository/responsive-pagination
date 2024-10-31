<?php

namespace ResponsivePagination\WP\Base\Admin;

use ResponsivePagination\Helper;

class AjaxHandler
{
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
        $this->handleSaveConvertPaginations();
        $this->handleSavePaginationSettings();
    }

    protected function handleSaveConvertPaginations()
    {
        add_action('wp_ajax_respg_save_convert_paginations', function () {
            if (!isset($_POST['storedConvertedElements'])) {
                wp_send_json_error(array('message' => 'Unrecognized format.'));
            }
            $storedConvertedElements = Helper::normalizeFormData($_POST['storedConvertedElements'], 'array');
            respg()->proxyStoredConvertedElements->updateAll($storedConvertedElements);
            wp_send_json_success(array('message' => 'Settings saved.'));
            wp_die();
        });
    }

    protected function handleSavePaginationSettings()
    {
        add_action('wp_ajax_respg_save_pagination_settings', function () {
            if (!isset($_POST['paginationSettings'])) {
                wp_send_json_error(array('message' => 'Unrecognized format.'));
            }
            $paginationSettings = Helper::normalizeFormData($_POST['paginationSettings'], 'object');
            respg()->proxyPaginationSettings->update('default', $paginationSettings);
            wp_send_json_success(array('message' => 'Settings saved', 'data' => json_encode($paginationSettings)));
        });
    }
}
