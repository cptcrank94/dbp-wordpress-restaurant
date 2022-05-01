<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Delete options


// Delete database
global $wpdb;
$tablesArr = [
    $wpdb->prefix . 'rmc_list',
    $wpdb->prefix . 'rmc_items',
    $wpdb->prefix . 'rmc_cat',
    $wpdb->prefix . 'rmc_links'
];
foreach( $tablesArr as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$table}");
}

?>
