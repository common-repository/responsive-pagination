<?php
/**
 * Public functions related to responsive-pagination plugin
 */

function respg_path($relative_path = '')
{
    $dirpath = dirname(RESPG_ENTRYFILE);
    return $relative_path ? $dirpath . '/' . $relative_path : $dirpath;
}

function respg_url($relative_path = '')
{
    return plugins_url($relative_path, RESPG_ENTRYFILE);
}
