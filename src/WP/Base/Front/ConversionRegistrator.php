<?php

namespace ResponsivePagination\WP\Base\Front;

/**
 * Handles registration of the stored elements (from the Admin Page), to be converted
 */
class ConversionRegistrator
{
    private static $registered = false;

    public static function register()
    {
        if (self::$registered) {
            return;
        }

        $storedConvertedElements = get_option('respg_stored_converted_elements', []);

        global $wp_query;
        $wpQueryExtractor = new WPQueryExtractor($wp_query);
        $current = $wpQueryExtractor->getCurrent();
        $total = $wpQueryExtractor->getTotal();
        $urlFirstPage = $wpQueryExtractor->getPagenumLink(1);
        $urlPattern = $wpQueryExtractor->getUrlPattern();
    
        foreach ($storedConvertedElements as $index => $element) {
            $paginationData = array(
                'id' => sprintf('converted-element-%d', $index + 1),
                'selector' => $element['selector'],
                'current' => $current,
                'total' => $total,
                'urlFirstPage' => $urlFirstPage,
                'urlPattern' => $urlPattern,
                'slugSettings' => apply_filters('respg_converted_element_slug_settings', 'default', $element)
            );

            // register
            respg()->front->paginationRegistrar->register($paginationData);
        }
    }
}
