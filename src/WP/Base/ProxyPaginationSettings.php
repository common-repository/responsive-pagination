<?php

namespace ResponsivePagination\WP\Base;

use ResponsivePagination\WP\Base\PageDetector;

/**
 * @todo Object of paginationSettings is called registeredSettings
 */
class ProxyPaginationSettings
{
    private $registeredSettings;

    public function __construct()
    {
        if (PageDetector::isOurPage()) {
            $this->hydrateData();
        }
    }

    /**
     * Retrieve paginationSettings.
     *
     * @param string $slug "all" means all, "used" means used only, other than that will return the particular slug
     * @return array
     */
    public function retrieve($slug = 'all')
    {
        if (!isset($this->registeredSettings)) {
            $this->hydrateData();
        }

        if ($slug === 'all') {
            return $this->registeredSettings;
        } elseif ($slug === 'used') {
            // return pagination settings that are used.
            $usedSlugSettings = [];
            $registeredPaginations = respg()->front->paginationRegistrar->retrieve();
            foreach ($registeredPaginations as $paginationData) {
                if (!in_array($paginationData['slugSettings'], $usedSlugSettings)) {
                    array_push($usedSlugSettings, $paginationData['slugSettings']);
                }
            }
            return $usedSlugSettings;
        } else {
            return array_key_exists($slug, $this->registeredSettings) ? $this->registeredSettings[$slug] : false;
        }
    }

    public function updateAll($registeredSettings)
    {
        $this->registeredSettings = $registeredSettings;
        $this->saveToDatabase();
    }

    public function update($slug, $paginationSettings, $preventSave = false)
    {
        $this->registeredSettings[$slug] = $paginationSettings;
        if (!$preventSave) {
            $this->saveToDatabase();
        }
    }

    /**
     * Load from database and store it to this class cache variable.
     * The data in the class cache would be replaced with the one from database.
     * Does not return anything.
     *
     * @return void
     */
    public function hydrateData()
    {
        $optionName = apply_filters('respg_pagination_settings_option_name', 'respg_pagination_settings');
        $this->registeredSettings = apply_filters('respg_load_pagination_settings', get_option($optionName, false));

        // if no record found on database, fill with initialValue & save it back to database.
        if ($this->registeredSettings === false) {
            $this->registeredSettings = $this->initialValue();
            $this->saveToDatabase();
        }
    }

    public function saveToDatabase()
    {
        $optionName = apply_filters('respg_pagination_settings_option_name', 'respg_pagination_settings');
        $valueToSave = apply_filters('respg_save_pagination_settings', $this->registeredSettings);
        update_option($optionName, $valueToSave);
    }

    public function initialBreakpointValue()
    {
        return array(
            'positioning' => 'center',
            'pagenumber' => array(
                'enabled' => true,
                'edge' => 1,
                'mid' => 2,
                'itemWidth' => 50,
            ),
            'prevNext' => array(
                'enabled' => true,
                'itemWidth' => 50,
            ),
            'firstLast' => array(
                'enabled' => false,
                'itemWidth' => 50,
            ),
            'content' => array(
                'numberedItem'=> '{pagenum}',
                'currentPage'=> '{current}',
                'dots'=> '...',
                'prevPage'=> '&lt;',
                'nextPage'=> '&gt;',
                'firstPage'=> '&Lt;',
                'lastPage'=> '&Gt;'
            )
        );
    }

    public function initialValue()
    {
        return array(
            'default' => array(
                'additionalClass' => array(
                    'numberedItem' => '',
                    'currentPage'=> '',
                    'dots'=> '',
                    'prevPage'=> '',
                    'nextPage'=> '',
                    'firstPage'=> '',
                    'lastPage'=> ''
                ),
                'styling' => array(
                    'borderWidth' => 1,
                    'borderRadius' => 5,
                    'fontSize' => 16,
                    'backgroundColor' => array (
                        'items' => array('r'=>0, 'g'=>0, 'b'=>0, 'a'=>1),
                        'itemsHover' => array('r'=>148, 'g'=>148, 'b'=>148, 'a'=>1),
                        'currentPage' => array('r'=>115, 'g'=>115, 'b'=>115, 'a'=>1),
                        'dots' => array('r'=>255, 'g'=>255, 'b'=>255, 'a'=>0)
                    ),
                    'borderColor' => array (
                        'items' => array('r'=>0, 'g'=>0, 'b'=>0, 'a'=>1),
                        'itemsHover' => array('r'=>200, 'g'=>200, 'b'=>200, 'a'=>1),
                        'currentPage' => array('r'=>200, 'g'=>200, 'b'=>200, 'a'=>1),
                        'dots' => array('r'=>255, 'g'=>255, 'b'=>255, 'a'=>0)
                    ),
                    'fontColor' => array (
                        'items' => array('r'=>255, 'g'=>255, 'b'=>255, 'a'=>1),
                        'itemsHover' => array('r'=>255, 'g'=>255, 'b'=>255, 'a'=>1),
                        'currentPage' => array('r'=>255, 'g'=>255, 'b'=>255, 'a'=>1),
                        'dots' => array('r'=>0, 'g'=>0, 'b'=>0, 'a'=>1)
                    )
                ),
                'breakpoints' => [
                    array(
                        'minWidth' => 320,
                        'positioning' => 'center',
                        'itemSpacing' => 5,     // spacing between items
                        'itemHeight' => 40,
                        'pagenumber' => array(
                            'enabled' => false,
                            'edge' => 1,
                            'mid' => 2,
                            'itemWidth' => 50,
                        ),
                        'prevNext' => array(
                            'enabled' => true,
                            'itemWidth' => 50,
                        ),
                        'firstLast' => array(
                            'enabled' => true,
                            'itemWidth' => 50,
                        ),
                        'content' => array(
                            'numberedItem'=> '{pagenum}',
                            'currentPage'=> '{current}',
                            'dots'=> '...',
                            'prevPage'=> '&lt;',
                            'nextPage'=> '&gt;',
                            'firstPage'=> '&Lt;',
                            'lastPage'=> '&Gt;'
                        )
                    ),
                    array(
                        'minWidth' => 480,
                        'positioning' => 'center',
                        'itemSpacing' => 5,     // spacing between items
                        'itemHeight' => 40,
                        'pagenumber' => array(
                            'enabled' => false,
                            'edge' => 1,
                            'mid' => 2,
                            'itemWidth' => 50,
                        ),
                        'prevNext' => array(
                            'enabled' => true,
                            'itemWidth' => 80,
                        ),
                        'firstLast' => array(
                            'enabled' => true,
                            'itemWidth' => 80,
                        ),
                        'content' => array(
                            'numberedItem'=> '{pagenum}',
                            'currentPage'=> '{current}',
                            'dots'=> '...',
                            'prevPage'=> '&lt; Prev',
                            'nextPage'=> 'Next &gt;',
                            'firstPage'=> '&Lt; First',
                            'lastPage'=> 'Last &Gt;'
                        )
                    ),
                    array(
                        'minWidth' => 720,
                        'positioning' => 'center',
                        'itemSpacing' => 5,     // spacing between items
                        'itemHeight' => 40,
                        'pagenumber' => array(
                            'enabled' => true,
                            'edge' => 1,
                            'mid' => 2,
                            'itemWidth' => 50,
                        ),
                        'prevNext' => array(
                            'enabled' => true,
                            'itemWidth' => 50,
                        ),
                        'firstLast' => array(
                            'enabled' => false,
                            'itemWidth' => 50,
                        ),
                        'content' => array(
                            'numberedItem'=> '{pagenum}',
                            'currentPage'=> '{current}',
                            'dots'=> '...',
                            'prevPage'=> '&lt;',
                            'nextPage'=> '&gt;',
                            'firstPage'=> '&Lt;',
                            'lastPage'=> '&Gt;'
                        )
                    ),
                    array(
                        'minWidth' => 1024,
                        'positioning' => 'center',
                        'itemSpacing' => 5,     // spacing between items
                        'itemHeight' => 40,
                        'pagenumber' => array(
                            'enabled' => true,
                            'edge' => 2,
                            'mid' => 3,
                            'itemWidth' => 50,
                        ),
                        'prevNext' => array(
                            'enabled' => true,
                            'itemWidth' => 80,
                        ),
                        'firstLast' => array(
                            'enabled' => false,
                            'itemWidth' => 50,
                        ),
                        'content' => array(
                            'numberedItem'=> '{pagenum}',
                            'currentPage'=> '{current}',
                            'dots'=> '...',
                            'prevPage'=> '&lt; Prev',
                            'nextPage'=> 'Next &gt;',
                            'firstPage'=> '&Lt;',
                            'lastPage'=> '&Gt;'
                        )
                    ),
                ]
            )
        );
    }
}
