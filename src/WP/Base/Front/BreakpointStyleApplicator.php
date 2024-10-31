<?php

namespace ResponsivePagination\WP\Base\Front;

class BreakpointStyleApplicator
{
    public static function apply()
    {
        $usedSlugSettings = respg()->proxyPaginationSettings->retrieve('used');

        $allBreakpointsStyle = '';
        foreach ($usedSlugSettings as $slugSettings) {
            $paginationSettings = respg()->proxyPaginationSettings->retrieve($slugSettings);
            $breakpoints = $paginationSettings['breakpoints'];

            // Each breakpoint
            foreach ($paginationSettings['breakpoints'] as $index => $breakpoint) {
                $breakpointStyle = '';
                // breakpoint's positioning style
                $breakpointStyle .= self::generateBreakpointPositioningStyle(
                    $slugSettings,
                    $breakpoint,
                    $index,
                    $paginationSettings['breakpoints']
                );

                // breakpoint's all items style
                $breakpointStyle .= self::generateBreakpointAllItemsStyle($slugSettings, $breakpoint);

                // breakpoint's each side style
                $breakpointStyle .= self::generateBreakpointSideStyle($slugSettings, $breakpoint);

                // breakpoint's each item type style
                $breakpointStyle .= self::generateBreakpointItemTypeStyle($slugSettings, $breakpoint);

                // media query
                $nextBreakpoint = $index < count($breakpoints) - 1 ? $breakpoints[$index + 1] : false;
                if ($nextBreakpoint !== false) {
                    $allBreakpointsStyle .= sprintf('@media screen and (min-width: %dpx) and (max-width: %dpx) { %s }', $breakpoint['minWidth'], $nextBreakpoint['minWidth'], $breakpointStyle);
                } else {
                    $allBreakpointsStyle .= sprintf('@media screen and (min-width: %dpx) { %s }', $breakpoint['minWidth'], $breakpointStyle);
                }
            }
        }
        printf('<style id="breakpoint-style">%s</style>', $allBreakpointsStyle);
    }

    /**
     * Generate positioning style, for every breakpoint
     *
     * @param string $slugSettings
     * @param array $breakpoint
     * @return string breakpointPositioningStyle
     */
    protected static function generateBreakpointPositioningStyle($slugSettings, $breakpoint, $breakpointIndex, $breakpoints)
    {
        // wide has fallback
        $navLinksDisplay = $breakpoint['positioning'] === 'wide' ? 'display: block; display: flex;' : 'display: block;';
        $navLinksAlign = in_array($breakpoint['positioning'], ['left', 'right']) ?
            sprintf('text-align: %s', $breakpoint['positioning']) : 'text-align: center;';
        $navLinksMargin = '';

        $firstPageStyle = '';
        $lastPageStyle = '';
        $prevPageStyle = '';
        $nextPageStyle = '';
        $wideFixingStyle = '';

        if ($breakpoint['positioning'] === 'wide') {
            // For allowing 'wide' positining
            if (!$breakpoint['prevNext']['enabled']) {
                $firstPageStyle = sprintf('.%s .respg-first { margin-right: auto; } ', $slugSettings);
                $lastPageStyle = sprintf('.%s .respg-last { margin-left: auto; } ', $slugSettings);
            }
            $prevPageStyle = sprintf('.%s .respg-prev { margin-right: auto; } ', $slugSettings);
            $nextPageStyle = sprintf('.%s .respg-next { margin-left: auto; } ', $slugSettings);

            // optimize a little bit. Only print style when it's necessary
            if (!$breakpoint['firstLast']['enabled']) {
                $firstPageStyle = '';
                $lastPageStyle = '';
            }
            if (!$breakpoint['prevNext']['enabled']) {
                $prevPageStyle = '';
                $nextPageStyle = '';
            }

            // visibility hidden (different with display:none)
            $wideFixingStyle = '.rvx { visibility: hidden; }';

            // navlink contains no margin left & right
            $navLinksMargin = 'margin-left: 0; margin-right: 0;';
        }
        
        $navLinksStyle = sprintf(
            '.responsive-pagination.%s .respg-nav-links { %s %s %s }',
            $slugSettings,
            $navLinksDisplay,
            $navLinksAlign,
            $navLinksMargin
        );

        return sprintf(
            '%s %s %s %s %s %s',
            $navLinksStyle,
            $firstPageStyle,
            $lastPageStyle,
            $prevPageStyle,
            $nextPageStyle,
            $wideFixingStyle
        );
    }


    /**
     * Generate style for each side of items, for every breakpoint
     * For example : Item spacing (using margin right/left)
     *
     * @param string $slugSettings
     * @param array $breakpoint
     * @return string
     */
    protected static function generateBreakpointSideStyle($slugSettings, $breakpoint)
    {
        $leftSideSpacingStyle = sprintf('margin-right: %dpx; margin-left: 0; ', $breakpoint['itemSpacing']);
        $rightSideSpacingStyle = sprintf('margin-left: %dpx; margin-right: 0; ', $breakpoint['itemSpacing']);
        $leftSideStyle = sprintf('.responsive-pagination.%1$s .respg-left { %2$s } ', $slugSettings, $leftSideSpacingStyle);
        $rightSideStyle = sprintf('.responsive-pagination.%1$s .respg-right { %2$s } ', $slugSettings, $rightSideSpacingStyle);

        // fix problem of double margin when pagenumber is disabled.
        // example : first  prev    next  last  -> between prev & next contains double margin
        $correctingStyle = '';
        if (!$breakpoint['pagenumber']['enabled']) {
            if ($breakpoint['prevNext']['enabled']) {
                $correctingStyle .= sprintf('.responsive-pagination.%1$s .respg-next { margin-left: 0; } ', $slugSettings);
            } else {
                $correctingStyle .= sprintf('.responsive-pagination.%1$s .respg-last { margin-left: 0; } ', $slugSettings);
            }
        }
        return sprintf('%s %s %s', $rightSideStyle, $leftSideStyle, $correctingStyle);
    }

    /**
     * Generate style for all items, for every breakpoint.
     * For example : item height & line height (same for all item within a breakpoint)
     *
     * @param string $slugSettings
     * @param array $breakpoint
     * @return string
     */
    protected static function generateBreakpointAllItemsStyle($slugSettings, $breakpoint)
    {
        $allItemsStyle = '';
        $allItemsStyle .= sprintf('height: %dpx; ', $breakpoint['itemHeight']);
        $allItemsStyle .= sprintf('line-height: %dpx; ', $breakpoint['itemHeight']);
        $allItemsStyle = sprintf('.responsive-pagination.%1$s .respg-item { %2$s } ', $slugSettings, $allItemsStyle);
        return sprintf('%s', $allItemsStyle);
    }

    /**
     * Generate style for each type of item, for every breakpoint.
     * For example : Item width.
     *
     * @param string $slugSettings
     * @param array $breakpoint
     * @return string
     */
    protected static function generateBreakpointItemTypeStyle($slugSettings, $breakpoint)
    {
        $pagenumbersItemWidthStyle = sprintf('width: %dpx; ', $breakpoint['pagenumber']['itemWidth']);
        $pagenumbersItemStyle = sprintf('.responsive-pagination.%1$s .respg-pagenum, .responsive-pagination.%1$s .respg-dots, .responsive-pagination.%1$s .respg-current { %2$s }', $slugSettings, $pagenumbersItemWidthStyle);

        $prevNextItemWidthStyle = sprintf('width: %dpx; ', $breakpoint['prevNext']['itemWidth']);
        $prevNextItemStyle = sprintf('.responsive-pagination.%1$s .respg-prev, .responsive-pagination.%1$s .respg-next { %2$s }', $slugSettings, $prevNextItemWidthStyle);
        
        $firstLastItemWidthStyle = sprintf('width: %dpx; ', $breakpoint['firstLast']['itemWidth']);
        $firstLastItemStyle = sprintf('.responsive-pagination.%1$s .respg-first, .responsive-pagination.%1$s .respg-last { %2$s }', $slugSettings, $firstLastItemWidthStyle);
        
        return sprintf('%s %s %s', $pagenumbersItemStyle, $prevNextItemStyle, $firstLastItemStyle);
    }
}
