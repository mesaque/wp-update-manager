<?php
/*
	Plugin Name: GMaps_on_Post_Page
	Plugin URI: http://konfeldt.com
	Description: GMaps_on_Post_Page enables you to embed a google map on a page and let it act responsively
	Version: 0.5
	Author: David Johnson
	Author URI: http://konfeldt.com
	License: WTFPL
	License URI: http://sam.zoy.org/wtfpl/ 

*/

function GMaps_on_Post_Page_registerShortcode($atts, $content = null) {
	extract(shortcode_atts(array(
      "width" => '640',
      "height" => '480',
      "flex" => 'true',
      "src" => 'https://maps.google.com/maps?q=New+York,+NY&hl=en&ll=40.714419,-74.0069&spn=0.005733,0.009645&sll=40.771637,-73.960648&sspn=0.045826,0.077162&oq=New+York+&hnear=New+York&t=m&z=17'
   ), $atts));
	
	$html = '<div ';
	
	if(!$flex) $html .= '>';
	else $html .= 'class="GMaps_on_Post_Page-embed-container">';
	
	$html .= '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" ';
	
	if(!$flex) {
		$html .= 'width="' . $width . '" height="' .$height .' "';
	}
	
	$html .= 'src="'.$src;
	$html .= '&output=embed"></iframe></div>';	
	
	return $html;
}

function GMaps_on_Post_Page_registerStyles() {
	
/*
  .embed-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    
    iframe, object, embed {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
  }
*/		
	$styles = "<!-- Styles for FlexGMaps -->\n";
	$styles .= '<style type="text/css">.GMaps_on_Post_Page-embed-container{position:relative; padding-bottom:56.25%;/* 16/9 ratio */height:0; overflow:hidden;} .GMaps_on_Post_Page-embed-container iframe, object, embed { position:absolute; top:0; left:0; width:100%; height:100%;}</style>';
	echo $styles;
}

add_action('wp_head', 'GMaps_on_Post_Page_registerStyles');
add_shortcode("GMaps_on_Post_Page", "GMaps_on_Post_Page_registerShortcode");

?>