#!/usr/bin/env php
<?php
$root_dirname = @$argv[1];
$WP_Path      = @$argv[2];

#Options for what helper need do
#1 for check if the current version os WP need a upgrade
#2 for return de plugin path
#3 for return world wide plugin version
$option  = @$argv[3];
$plugin_ = @$argv[4];

if( null == $WP_Path ) exit(1);
if( null == $root_dirname ) exit(1);
if( null == $option ) exit(1);

$wp_version = "2.3";

if ( file_exists( $WP_Path . '/wp-includes/version.php' ) ):
	require( $WP_Path . '/wp-includes/version.php' );
endif;

switch ( $option ):
	case 1:
		_check_wp_version();
		break;
	case 2:
		echo $WP_Path . '/wp-content/plugins' ;
		exit(0);
		break;
	case 3:
		_check_plugin_version( $plugin_ );
		break;
endswitch;



function _check_wp_version()
{
	global 	$wp_version;
	$wordpressORG = get_headers("https://wordpress.org/latest.zip", 1);
	$wp_version_ORG = preg_replace( array('#.*wordpress-#', '#\.zip.*#') , '', $wordpressORG['Content-Disposition']);
	if( $wp_version_ORG <= $wp_version ) exit(1);

	exit(0);
}

function _check_plugin_version( $plugin = null )
{
    if( null == $plugin ) exit('');

    $plugin_exist = @get_headers( sprintf( "https://wordpress.org/plugins/%s/", $plugin ), 1 );
    if( null == $plugin_exist ) exit('');

    $html = @file_get_contents( sprintf( "https://wordpress.org/plugins/%s/", $plugin ) );
    $a_link = preg_match( '#<a.*plugin-download.*</a>#i', $html, $href_array );
    if( 1 != $a_link ) exit('');

    $version = preg_match( '#<li.*Version.*<strong>.*</li>#i',  $html, $version_ );
    $href_ = preg_replace( array( "#.*href=('|\")#" , "#('|\")>.*#"), '', $href_array[0]);
    $version_ = preg_replace( array("#.*<strong>#", "#</strong>.*#"), '', $version_[0] );
    echo $version_.';'.$href_;
    exit(0);
}

exit(0);