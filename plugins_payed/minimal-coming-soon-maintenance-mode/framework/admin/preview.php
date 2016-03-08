<?php

/**
 * Preview maintenance mode.
 *
 * @link       http://www.69signals.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 */

// Load WordPress
require_once( explode( "wp-content" , __FILE__ )[0] . "wp-load.php" );


// Include important files
require_once '../public/include/functions.php';


// Plugin options from the database
$signals_csmm_options = get_option( 'signals_csmm_options' );


// Localization
load_plugin_textdomain( 'signals', false, SIGNALS_CSMM_PATH . 'framework/langs' );


// Getting custom login URL for the admin
$signals_login_url = wp_login_url();


// Checking for the server protocol status
if ( isset( $_SERVER['HTTPS'] ) === true ) {
	$signals_protocol = 'https';
} else {
	$signals_protocol = 'http';
}


// This is the server address of the current page
$signals_server_url = $signals_protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


// Checking for the custom_login_url value
if ( empty( $signals_csmm_options['custom_login_url'] ) ) {
	$signals_csmm_options['custom_login_url'] = NULL;
}


// Render the maintenance mode template
csmm_render_template( $signals_csmm_options );