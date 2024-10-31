<?php

namespace ResponsivePagination\WP\Base\Front;

class PaginationRegistrar
{
    private $registeredPaginations = [];

    /**
     * Register pagination
     *
     * @param array $paginationData {
     *  @type string $selector
     *  @type integer $current
     *  @type integer $total
     *  @type string $urlFirstPage
     *  @type string $urlPattern
     * }
     * @todo Validation for paginationData
     * @return void
     */
    public function register($paginationData)
    {
        array_push($this->registeredPaginations, $paginationData);
    }

    /**
     * Retrieve all registered paginations
     *
     * @return array
     */
    public function retrieve()
    {
        return $this->registeredPaginations;
    }
}
