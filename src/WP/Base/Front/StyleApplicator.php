<?php

namespace ResponsivePagination\WP\Base\Front;

use ResponsivePagination\Helper;

/**
 *  This class deals with applying style in front
 */
class StyleApplicator
{
    /**
     * Apply some style
     *
     * @todo Perhaps add little bit more efficiency in printed styles?
     * @return void
     */
    public static function apply()
    {
        // retrieve all registered paginations
        $registeredPaginations = respg()->front->paginationRegistrar->retrieve();

        // collect paginationSettings that are used in registeredPaginations
        $usedSlugSettings = [];
        foreach ($registeredPaginations as $paginationData) {
            if (!in_array($paginationData['slugSettings'], $usedSlugSettings)) {
                array_push($usedSlugSettings, $paginationData['slugSettings']);
            }
        }

        // generate responsiveness Style
        self::applyResponsivenessStyle($usedSlugSettings);

        // generate visual style
        self::applyVisualStyle($usedSlugSettings);
    }

    protected static function applyVisualStyle($slugs)
    {
    }

    protected static function applyResponsivenessStyle($slugs)
    {
    }
}
