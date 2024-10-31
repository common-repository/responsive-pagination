<?php

namespace ResponsivePagination\WP\Base\Front;

use ResponsivePagination\Helper;

class APIPaginator
{
    public function registerPagination($arguments)
    {
        // create pagination placeholder element
        $this->createPaginationPlaceholder($arguments[0]);

        // extract paginationData
        $paginationData = $this->extractPaginationData($arguments);

        // register
        respg()->front->paginationRegistrar->register($paginationData);
    }

    protected function createPaginationPlaceholder($id)
    {
        printf('<div id="%s"></div>', Helper::stringToKebabCase($id));
    }

    protected function inputArgsHasProblems($arguments)
    {
        $id = $arguments[0];
        $query = $arguments[1];
        $problems = [];

        if (!is_string($id)) {
            array_push($problems, 'First argument is the ID, and should be a string (kebab-case).');
        }
        if (!$query instanceof \WP_Query && !is_array($query)) {
            array_push($problems, 'Second argument should be either a WP_Query instance or an array.');
        }
        if (is_array($query)) {
            if (!array_key_exists('current', $query)) {
                array_push($problems, 'If args is array, it should contain "current"');
            }
            if (!array_key_exists('total', $query)) {
                array_push($problems, 'If args is array, it should contain "total"');
            }
            if (!array_key_exists('url_pattern', $query) && !array_key_exists('urlPattern', $query)) {
                array_push($problems, 'If args is array, it should contain "url_pattern"');
            }
        }
    }

    protected function extractPaginationData($arguments)
    {
        $paginationData = array();
        if ($arguments[1] instanceof \WP_Query) {
            $extractor = new WPQueryExtractor($arguments[1]);
            $paginationData['current'] = $extractor->getCurrent();
            $paginationData['total'] = $extractor->getTotal();
            $paginationData['urlPattern'] = $extractor->getUrlPattern();
            $paginationData['urlFirstPage'] = $extractor->getPagenumLink(1);
        } else {
            $paginationData['current'] = intval($arguments[1]['current']);
            $paginationData['total'] = intval($arguments[1]['total']);

            if (array_key_exists('url_pattern', $arguments[1])) {
                $paginationData['urlPattern'] = $arguments[1]['url_pattern'];
            } else {
                $paginationData['urlPattern'] = $arguments[1]['urlPattern'];
            }
            
            if (array_key_exists('url_first_page', $arguments[1])) {
                $paginationData['urlFirstPage'] = $arguments[1]['url_first_page'];
            } elseif (array_key_exists('urlFirstPage', $arguments[1])) {
                $paginationData['urlFirstPage'] = $arguments[1]['urlFirstPage'];
            } else {
                $paginationData['urlFirstPage'] = str_replace('{pagenum}', '1', $paginationData['urlPattern']);
            }
        }
        $paginationData['id'] = $arguments[0];
        $paginationData['selector'] = sprintf('#%s', Helper::stringToKebabCase($paginationData['id']));
        $paginationData['slugSettings'] = apply_filters('respg_slug_settings', 'default', $arguments);
        return $paginationData;
    }
}
