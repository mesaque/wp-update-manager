<?php 
/* 
Plugin Name: Test Tag
Plugin URI: http://www.meiteilue.net/
Description: Allow to tag as test some content (posts, pages, medias, custom posts, categories, widgets) that you can delete in one click 
Version: 1.1 
Author: Julien Zerbib
Author URI: http://www.free-seed.info/
  
  
    Copyright 2013  Julien Zerbib  (email : or.n.juz@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Test_Tag {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // Initialize Settings
        require_once( dirname(__FILE__) . '/settings.php' );
        $Test_Tag_Settings = new Test_Tag_Settings();
        
        // Initialize Post Tools
        require_once( dirname(__FILE__) . '/inc/post.php' );
        $Test_Tag_Post = new Test_Tag_Post();
        
        // Initialize Bulk Post Tools
        require_once( dirname(__FILE__) . '/inc/bulk-post.php' );
        $Test_Tag_Bulk_Post = new Test_Tag_Bulk_Post();
        
        // Initialize Category Tools
        require_once( dirname(__FILE__) . '/inc/category.php' );
        $Test_Tag_Category = new Test_Tag_Category();
        
        // Initialize Bulk Category Tools
        require_once( dirname(__FILE__) . '/inc/bulk-category.php' );
        $Test_Tag_Bulk_Category = new Test_Tag_Bulk_Category();
        
        // Initialize Comment Tools
        require_once( dirname(__FILE__) . '/inc/comment.php' );
        $Test_Tag_Comment = new Test_Tag_Comment();
        
        // Initialize Bulk Comment Tools
        require_once( dirname(__FILE__) . '/inc/bulk-comment.php' );
        $Test_Tag_Bulk_Comment = new Test_Tag_Bulk_Comment();
        
        // Initialize Widget Tools
        require_once( dirname(__FILE__) . '/inc/widget.php' );
        $Test_Tag_Widget = new Test_Tag_Widget();
        
        // Initialize Delete Content Actions
        require_once( dirname(__FILE__) . '/inc/delete.php' );
        $Test_Tag_Delete = new Test_Tag_Delete();
    } // END public function __construct
    
    /**
     * Activate the plugin
     */
    public static function activate() {
        // Do nothing
    } // END public static function activate

    /**
     * Deactivate the plugin
     */        
    public static function deactivate() {
        // Do nothing
    } // END public static function deactivate
} // END class Test_Tag

// Installation and uninstallation hooks
register_activation_hook( __FILE__, array( 'Test_Tag', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Test_Tag', 'deactivate' ) );

// instantiate the plugin class
$test_tag_plugin = new Test_Tag();
