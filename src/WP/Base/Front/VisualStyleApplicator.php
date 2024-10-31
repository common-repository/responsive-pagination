<?php

namespace ResponsivePagination\WP\Base\Front;

use ResponsivePagination\Helper;

class VisualStyleApplicator
{
    public static function apply()
    {
        $usedSlugSettings = respg()->proxyPaginationSettings->retrieve('used');
        $itemStyle = '';
        $dotsStyle = '';
        $currentPageStyle = '';
        $itemHoverStyle = '';
        foreach ($usedSlugSettings as $slugSettings) {
            $paginationSettings = respg()->proxyPaginationSettings->retrieve($slugSettings);
            $styling = $paginationSettings['styling'];

            // applied to normal .respg-Item
            $itemStyle .= sprintf('font-size: %dpx; ', $styling['fontSize']);
            $itemStyle .= sprintf('border-width: %dpx; ', $styling['borderWidth']);
            $itemStyle .= sprintf('border-radius: %dpx; ', $styling['borderRadius']);
            $itemStyle .= sprintf('color: %s; ', Helper::colorToString($styling['fontColor']['items']));
            $itemStyle .= sprintf('border-color: %s; ', Helper::colorToString($styling['borderColor']['items']));
            $itemStyle .= sprintf('background-color: %s; ', Helper::colorToString($styling['backgroundColor']['items']));

            // applied to .respg-item:hover
            $itemHoverStyle .= sprintf('color: %s; ', Helper::colorToString($styling['fontColor']['itemsHover']));
            $itemHoverStyle .= sprintf('border-color: %s; ', Helper::colorToString($styling['borderColor']['itemsHover']));
            $itemHoverStyle .= sprintf('background-color: %s; ', Helper::colorToString($styling['backgroundColor']['itemsHover']));

            // applied to .respg-current
            $currentPageStyle .= sprintf('color: %s; ', Helper::colorToString($styling['fontColor']['currentPage']));
            $currentPageStyle .= sprintf('border-color: %s; ', Helper::colorToString($styling['borderColor']['currentPage']));
            $currentPageStyle .= sprintf('background-color: %s; ', Helper::colorToString($styling['backgroundColor']['currentPage']));

            // applied to .respg-dots
            $dotsStyle .= sprintf('color: %s; ', Helper::colorToString($styling['fontColor']['dots']));
            $dotsStyle .= sprintf('border-color: %s; ', Helper::colorToString($styling['borderColor']['dots']));
            $dotsStyle .= sprintf('background-color: %s; ', Helper::colorToString($styling['backgroundColor']['dots']));

            // additional (not related to settings)
            $itemStyle .= 'text-align: center; ';
            $currentPageStyle .= 'margin-left: 0; margin-right: 0; ';

            // wrap with selector
            $itemStyle = sprintf('.responsive-pagination.%s .respg-item { %s }', $slugSettings, $itemStyle);
            $currentPageStyle = sprintf('.responsive-pagination.%s .respg-current { %s }', $slugSettings, $currentPageStyle);
            $dotsStyle = sprintf('.responsive-pagination.%s .respg-dots { %s }', $slugSettings, $dotsStyle);

            $itemHoverStyle = sprintf('.responsive-pagination.%1$s .respg-pagenum:hover, .responsive-pagination.%1$s .respg-prev:hover, .responsive-pagination.%1$s .respg-next:hover, .responsive-pagination.%1$s .respg-first:hover, .responsive-pagination.%1$s .respg-last:hover { %2$s }', $slugSettings, $itemHoverStyle);

            printf('<style id="visual-style">%s %s %s %s</style>', $itemStyle, $itemHoverStyle, $currentPageStyle, $dotsStyle);
        }
    }
}
