<?php

class Test_Tag_Bulk_Category {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_action( 'wp_ajax_get_test_tag_bulk_categories_inner_custom_box', array( &$this, 'inner_custom_box' ) );
        add_action( 'wp_ajax_update_test_tag_bulk_categories', array( &$this, 'save_metadatas' ) );
        
        add_action( 'admin_footer-edit-tags.php', array( &$this, 'admin_footer' ) );
    } // END public function __construct

    /**
     * Prints the box content.
     */
    public function inner_custom_box() {

        // Render the bulk-post-custom-box template
        include( dirname(__FILE__) . '/../views/bulk-category-custom-box.php' );

    } // END public function inner_custom_box

    /**
     * When Bulk Update Categories, saves our custom data.
     */
    public function save_metadatas() {
        $test_tag = $_POST['test_tag'];
        $count = 0;
        $category_test_tag = get_option( 'category_test_tag' );
        
        if( !isset( $category_test_tag ) )
            $category_test_tag = array();

        foreach( $_POST['cat_ids'] as $cat_id ) {
            $cat_id = (int) $cat_id;
            
            if( $cat_id != 0 ) {
                if ( ! current_user_can( 'manage_categories', $cat_id ) )
                    die;
                            
                $category_test_tag[$cat_id] = $test_tag;
                update_option( 'category_test_tag', $category_test_tag );
                
                $count++;
            }
        }
        
        printf( __( '%1d categories updated', 'tt' ), $count ); 
        
        die;
    } // END public function save_metadatas

    /**
    * Add bulk action on category page
    */
    public function admin_footer() {

        // Render the bulk-post-custom-box template
        include( dirname(__FILE__) . '/../views/bulk-category-scripts.php' );

    } // END public function admin_footer
} // END class Test_Tag_Bulk_Category
