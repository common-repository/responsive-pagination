<?php

namespace ResponsivePagination\WP\Base\Front;

/**
 * Handles data extraction needed for Responsive Pagination, from WP_Query
 */
class WPQueryExtractor
{
    private $query;

    public function __construct(\WP_Query $query)
    {
        $this->query = $query;
    }

    public function getCurrent()
    {
        return max(1, $this->query->get('paged', 1));
    }

    public function getTotal()
    {
        return $this->query->max_num_pages;
    }

    public function getPagenumLink(int $pagenum)
    {
        return get_pagenum_link($pagenum);
    }

    /**
     * Get URL pattern by doing iterations
     *
     * @param integer $iteration How many iterations would be executed. Should be more than 10
     * @return void
     */
    public function getUrlPattern($iteration = 20)
    {
        // storage
        $bunker = [];

        // fill bunker
        for ($i = 1; $i <= $iteration; $i++) {
            $url     = $this->getPagenumLink($i);
            $pattern = str_replace($i, '{pagenum}', $url);

            $exist = false;
            foreach ($bunker as $index => $item) {
                if ($item['pattern'] === $pattern) {
                    $bunker[ $index ]['count']++;
                    $exist = true;
                    break;
                }
            }
            if (! $exist) {
                array_push(
                    $bunker,
                    array(
                        'pattern' => $pattern,
                        'count'   => 1,
                    )
                );
            }
        }

        // find the item in bunker with highest count
        $max       = 0; // max count
        $index_max = 0; // index with max count
        foreach ($bunker as $index => $item) {
            if ($item['count'] > $max) {
                $max       = $item['count'];
                $index_max = $index;
            }
        }

        return $bunker[ $index_max ]['pattern'];
    }
}
