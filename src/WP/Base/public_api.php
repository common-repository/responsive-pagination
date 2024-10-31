<?php

/**
 * Create responsive pagination programmatically.
 *
 * What this function does, is register to the responsive pagination filter hook
 *
 * @param WP_Query|array $args Args could be instance of WP_Query or custom array
 * @return void
 */
function create_responsive_pagination()
{
    $arguments = func_get_args();
    respg()->front->apiPaginator->registerPagination($arguments);
}
