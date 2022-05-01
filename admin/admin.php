<?php
function rmc_items_page_html() {
    require_once RMC_PLUGIN_DIR . '/admin/templates/articles.php';
}

function rmc_menus_page_html() {
    require_once RMC_PLUGIN_DIR . '/admin/templates/menucards.php';
}

function rmc_options_page_html() {
    require_once RMC_PLUGIN_DIR . '/admin/templates/settings.php';
}

function rmc_overview_page_html() {
    require_once RMC_PLUGIN_DIR . '/admin/templates/overview.php';
}

?>