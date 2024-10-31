<?php

namespace ResponsivePagination\WP\Base;

use ResponsivePagination\WP\Base\PageDetector;

class ProxyStoredConvertedElements
{
    private $storedConvertedElements;

    public function __construct()
    {
        if (PageDetector::isOurPage()) {
            $this->hydrateData();
        }
    }

    public function retrieve()
    {
        if (!isset($this->storedConvertedElements)) {
            $this->hydrateData();
        }
        return $this->storedConvertedElements;
    }

    public function updateAll($storedConvertedElements)
    {
        $this->storedConvertedElements = $storedConvertedElements;
        $this->saveToDatabase();
    }

    public function hydrateData()
    {
        $optionName = apply_filters('respg_stored_converted_elements_option_name', 'respg_stored_converted_elements');
        $this->storedConvertedElements = apply_filters(
            'respg_load_stored_converted_elements',
            get_option($optionName, false)
        );

        // if no record found on database, fill with initialValue & save it back to database.
        if ($this->storedConvertedElements === false) {
            $this->storedConvertedElements = $this->initialValue();
            $this->saveToDatabase();
        }
    }

    protected function saveToDatabase()
    {
        $optionName = apply_filters('respg_stored_converted_elements_option_name', 'respg_stored_converted_elements');
        $valueToSave = apply_filters('respg_save_stored_converted_elements', $this->storedConvertedElements);
        update_option($optionName, $valueToSave);
    }

    protected function initialValue()
    {
        return [
            array(
                'selector' => '.navigation.pagination',
                'note' => 'Common WordPress paginations'
            )
        ];
    }
}
