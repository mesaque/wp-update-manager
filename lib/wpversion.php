#!/usr/bin/env php
<?php

$root_dirname = @$argv[1];
$WP_Path      = @$argv[2];

if( null == $WP_Path ) exit(1);
if( null == $WP_Path ) exit(1);
( $WP_Path . '/wp-blog-header.php' ) or exit(1);
@require( $WP_Path . '/wp-blog-header.php' );
$wp_current_version =  floatval( $wp_version );
require( $root_dirname . '/wordpress/wp-includes/version.php' );
$wp_api_version = floatval( $wp_version );

if( $wp_api_version <= $wp_current_version ) exit(1);

exit(0);
