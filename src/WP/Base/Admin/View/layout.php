<div id="respg" class="wrap">
    <form id="main-form" method="POST">
        <?php // Page Title (already in form of html) // ?>
        <?php echo $this->pageTitle; ?>

        <div id="notification-area"></div>

        <?php // notification ?>
        <?php if (respg()->admin->saveNotification === 'success') : ?>
        <div id="wrapper-notification-areaku">
            <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                <p><strong>Settings saved.</strong></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
        </div>
        <?php endif ?>

        <?php // Tabs Navigation // ?>
        <nav class="nav-tab-wrapper">
            <?php foreach ($this->tabs as $index => $tab) { ?>
                <?php $className = $this->activeTabIndex === $index ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>
                <a 
                    class="<?php echo $className ?>" 
                    href="<?php printf('?page=responsive-pagination.php&tab=%s', $tab['slug']) ?>"
                >
                    <?php echo $tab['title'] ?>
                </a>
            <?php } ?>
        </nav>

        <?php // Screen reader title // ?>
        <h1 class="screen-reader-text"><?php echo $activeTab['title'] ?></h1>
        <h2 class="screen-reader-text"><?php echo $activeTab['subTitle'] ?></h2>

        <?php // Content Tab // ?>
        <!-- React -->
        <div id="<?php printf("%s-tab-content", $activeTab['slug']) ?>" class="tab-content">
            <p>This settings needs javascript enabled & running. If the content not loaded, something may blocking it.</p>
        </div>  
        <!-- End React -->

        <input name="respg_tab_name" type="hidden" value="<?php echo $activeTab['slug']; ?>" />
    </form>
</div>
