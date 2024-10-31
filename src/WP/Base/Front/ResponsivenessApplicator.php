<?php

namespace ResponsivePagination\WP\Base\Front;

class ResponsivenessApplicator
{
    public static function apply()
    {
        $usedSlugSettings = respg()->proxyPaginationSettings->retrieve('used');

        $responsivenessStyle = '';
        foreach ($usedSlugSettings as $slug) {
            $paginationSettings = respg()->proxyPaginationSettings->retrieve($slug);

            // by default it's not displayed, affects below minimum breakpoint
            // Note : If we're using media query with max-width using the lowest breakpoint - 1 and set display: none, in reality it still showing in Firefox (worse, it will show all breakpoint's content)
            $responsivenessStyle .= sprintf('.%1$s .rx0, .%1$s .rv0 { display: none; }', $slug);

            // each breakpoint
            foreach ($paginationSettings['breakpoints'] as $index => $breakpoint) {
                $responsivenessStyle .= self::generateBreakpointResponsivenessStyle(
                    $slug,
                    $breakpoint,
                    $index
                );
            }
        }
        printf('<style id="responsiveness">%s</style>', $responsivenessStyle);
    }

    /**
     * Generate responsiveness style per breakpoint
     *
     * @param string $slugSettings
     * @param array $breakpoint
     * @param int $breakpointIndex
     * @return string breakpointResponsivenessStyle
     */
    protected static function generateBreakpointResponsivenessStyle($slugSettings, $breakpoint, $breakpointIndex)
    {
        $hidden = sprintf('.%s .rx%d { display: none !important; }', $slugSettings, $breakpointIndex);
        $showed = sprintf('.%s .rv%d { display: inline-block !important; }', $slugSettings, $breakpointIndex);
        
        // For Breakpoint Responsiveness Style, we're using just min-width for media query
        return sprintf(' @media screen and (min-width: %dpx) { %s %s } ', $breakpoint['minWidth'], $showed, $hidden);
    }
}
