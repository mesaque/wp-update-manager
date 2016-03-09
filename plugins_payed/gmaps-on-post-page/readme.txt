=== Plugin Name ===
Contributors: konfeldt
Donate link: http://konfeldt.com/
Tags: GMaps, GMap, map, cart, responsive
Requires at least: 3.3.1
Tested up to: 3.5.1
Stable tag: trunk
License: WTFPL
License URI: http://www.wtfpl.net

GMaps_on_Post_Page is a wordpress plugin that allows you to embed a google map in a page or post and let the width and height scale with a responsive design.

== Description ==

GMaps_on_Post_Page is a wordpress plugin that allows you to embed a google map in a page or post and let the width and height scale with a responsive design.

Available Params:
* flex: boolean - specify if the map object should scale
* width: int - width of the static map
* height: int - height of the static map
* src: string - source of the google map to embed

Insert the following shortcode into a post or page:
* Flexible Example: `[GMaps_on_Post_Page src="https://maps.google.com/maps?q=London,+UK" ]`
* Static Example: `[GMaps_on_Post_Page src="https://maps.google.com/maps?q=London,+UK" flex="false" width="640" height="480" ]`

Documentation is available via [Konfeldt.com](http://konfeldt.com/)

== Installation ==

1. Upload `GMaps_on_Post_Page.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Insert the shortcode into a post or page.

== Changelog ==

= 0.5 =
* Initial version



