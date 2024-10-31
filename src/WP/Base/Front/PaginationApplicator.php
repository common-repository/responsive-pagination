<?php

namespace ResponsivePagination\WP\Base\Front;

/**
 * Handles applying registered paginations
 */
class PaginationApplicator
{
    private static $applied = false;

    /**
     * Apply registered paginations
     *
     * @return void
     */
    public static function apply()
    {
        if (self::$applied) {
            return;
        }

        // retrieve registered paginations
        $registeredPaginations = respg()->front->paginationRegistrar->retrieve();

        // retrieve registered paginations settings
        $registeredPaginationSettings = respg()->proxyPaginationSettings->retrieve();

        // send registeredPaginations into javascript
        $registeredPaginationsDeclaration = sprintf(
            'window._respg.registeredPaginations = %s;',
            json_encode($registeredPaginations)
        );
        $registeredPaginationSettingsDeclaration = sprintf(
            'window._respg.registeredPaginationSettings = %s;',
            json_encode($registeredPaginationSettings)
        );
        printf(
            '<script>%s %s</script>',
            $registeredPaginationsDeclaration,
            $registeredPaginationSettingsDeclaration
        );
    }
}
