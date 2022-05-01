<?php

/**
 * Plugin Name:     Restaurant Menu Card
 * Plugin URI:      https://www.dreambowl.de/
 * Description:     Creates a online restaurant menu card for your website
 * Version:         1.0.0
 * Author:          Dominic Bryan Ingram
*/

defined('ABSPATH') or die( 'Hey, what are you doing here?' );

define( 'RMC_TEXTDOMAIN', 'rmc-plugin');
define( 'RMC_PLUGIN', __FILE__);
define( 'RMC_PLUGIN_BASENAME', plugin_basename( RMC_PLUGIN ) );
define( 'RMC_PLUGIN_NAME', trim( dirname( RMC_PLUGIN_BASENAME ), '/' ) );
define( 'RMC_PLUGIN_DIR', untrailingslashit( dirname( RMC_PLUGIN ) ) );

require_once RMC_PLUGIN_DIR . '/load.php';

// Activation Hook
register_activation_hook( __FILE__, 'registerPlugin' );
add_action( 'admin_menu', 'rmc_create_pages' );
add_action( 'admin_init', 'rmc_settings_init' );
add_action( 'admin_enqueue_scripts', 'rmc_enqueue_stylesandscripts');

?>