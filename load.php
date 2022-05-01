<?php

function registerPlugin() {
    global $wpdb;

    $sql = array();

    $dbTableList = $wpdb->prefix . 'rmc_list';
    $dbTableItems = $wpdb->prefix . 'rmc_items';
    $dbTableCategory = $wpdb->prefix . 'rmc_cat';
    $dbTableLinks = $wpdb->prefix . 'rmc_links';
    $charset = $wpdb->get_charset_collate();

    // Check if table already exists, if not, create it
    if ( $wpdb->get_var( "show tables like '$dbTableList'") !== $dbTableList ) {
        $sql[] = "CREATE TABLE $dbTableList (
                menuID int(11) NOT NULL auto_increment,
                menuName varchar(30) NOT NULL,
                UNIQUE KEY id (menuID)            
            ) $charset;";
    }

    if ( $wpdb->get_var( "show tables like '$dbTableItems'") !== $dbTableItems ) {
        $sql[] = "CREATE TABLE $dbTableItems (
                itemID int(11) NOT NULL auto_increment,
                parentID int(11) NOT NULL,
                itemName varchar(50) NOT NULL,
                itemPrice varchar(10) NOT NULL,
                itemCat varchar(10) NOT NULL,
                itemDesc text NOT NULL,
                UNIQUE KEY id (itemID)            
            ) $charset;";
    }

    if ( $wpdb->get_var( "show tables like '$dbTableCategory'") !== $dbTableCategory ) {
        $sql[] = "CREATE TABLE $dbTableCategory (
                catID int(11) NOT NULL auto_increment,
                catName varchar(30) NOT NULL,             
                UNIQUE KEY id (catID)            
            ) $charset;";
    }

    if ( $wpdb->get_var( "show tables like '$dbTableLinks'") !== $dbTableLinks ) {
        $sql[] = "CREATE TABLE $dbTableLinks (
                linkID int(11) NOT NULL auto_increment,
                menuID int(11) NOT NULL,
                catID int(11) NOT NULL,  
                UNIQUE KEY id (linkID)            
            ) $charset;";
    }

    if( !empty($sql) ) {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
        add_option( 'test_db_version' , $test_db_version );
    }
}

function rmc_create_pages() {
    // Create main admin menu
    add_menu_page( 'Übersicht', 'RMC', 'manage_options', 'rmc', 'rmc_overview_page_html' );

    // Create submenus
    add_submenu_page('rmc', 'RMC Speisekarte', 'Speisekarte', 'manage_options', 'rmc_menucards', 'rmc_menus_page_html' );
    add_submenu_page('rmc', 'RMC Artikel', 'Artikel', 'manage_options', 'rmc_items', 'rmc_items_page_html' );
    add_submenu_page( 'rmc', 'Einstellungen', 'Einstellungen', 'manage_options', 'rmc_options', 'rmc_options_page_html' );

    // Create settings filter link
    add_filter( 'plugin_action_link' . RMC_PLUGIN_BASENAME, '<a href="admin.php?page=rmc_options">Settings</a>');
}

function rmc_settings_init() {
    add_settings_section('rmc_general_settings_section', 'Allgemeine Einstellungen', null, 'rmc_general');
    add_settings_field(
        'rmc_template_default',
        'Standard-Template', 
        'rmc_generate_templateSelect', 
        'rmc_general', 
        'rmc_general_settings_section');
    register_setting('rmc_general_settings_section', 'rmc_template_default');
}

function rmc_generate_templateSelect() {
    ?>

        <select name="template-select">
            <option value="default" <?php selected(get_option('rmc_template_default'), "default"); ?>>Default</option>
            <option value="another" <?php selected(get_option('rmc_template_default'), "another"); ?>>Another</option>
        </select>

    <?php
}

function rmc_enqueue_stylesandscripts() {
    wp_enqueue_style('rcm_admin_style', plugins_url('/admin/css/style.css', __FILE__));
    wp_enqueue_script('rcm_admin_script', plugins_url('/admin/js/script.js', __FILE__));
}

?>