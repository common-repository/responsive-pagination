<?php

namespace ResponsivePagination\WP\Base\Admin;

class AdminPage
{
    /**
     * @var array {
     *      Array of admin tabs.
     *      @type string $slug
     *      @type string $title
     *      @type string $scriptUrl
     * }
     */
    private $tabs;

    /**
     * Current active tab
     *
     * @var int
     */
    private $activeTabIndex;
    
    /**
     * Page title. Can be in format of HTML
     *
     * @var string
     */
    private $pageTitle;

    public function __construct($menuTitle, $menuSlug, $htmlTitle, $pageTitle, $tabs, $capability, $position = null)
    {
        add_options_page(
            $htmlTitle, // title tag of html (head)
            $menuTitle,
            $capability,
            $menuSlug,
            array($this, 'pageContent'),
            $position
        );
        // page title
        $this->pageTitle = strpos($pageTitle, '<') === 0 ?
            $pageTitle : sprintf('<h1>%s</h1>', $pageTitle);

        $this->tabs = $tabs;
        $this->setActiveTab();
    }

    public function pageContent()
    {
        $activeTab = $this->tabs[$this->activeTabIndex];
        $storedConvertedElements = respg()->proxyStoredConvertedElements->retrieve();
        $paginationSettings = respg()->proxyPaginationSettings->retrieve('default');

        // layout
        require_once(respg_path('src/WP/Base/Admin/View/layout.php'));
    }

    /**
     * Read tab parameter from $_GET, then set it into active tab
     *
     * @return void
     */
    protected function setActiveTab()
    {
        $activeTabSlug = isset($_GET['tab']) ? $_GET['tab'] : false;

        if ($activeTabSlug) {
            $found = false;
            foreach ($this->tabs as $index => $tab) {
                if ($tab['slug'] === $activeTabSlug) {
                    $this->activeTabIndex = $index;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->activeTabIndex = 0;
            }
        } else {
            $this->activeTabIndex = 0;
        }
    }
}
