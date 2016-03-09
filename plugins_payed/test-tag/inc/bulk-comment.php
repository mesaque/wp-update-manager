<?php

class Test_Tag_Bulk_Comment {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_filter( 'current_screen', array( &$this, 'comment_screen' ) );
        add_action( 'wp_ajax_get_test_tag_bulk_comments_inner_custom_box', array( &$this, 'inner_custom_box' ) );
        add_action( 'wp_ajax_update_test_tag_bulk_comments', array( &$this, 'save_metadatas' ) );
    } // END public function __construct

    /**
    * Add bulk action on comment page
    */
    public function comment_screen( $screen ) {
        if ( isset( $screen->id ) && $screen->id == 'edit-comments' ) {
            add_action( 'admin_footer', array( &$this, 'admin_footer' ) );
        }
        return $screen;
    } // END public function comment_screen

    /**
     * Prints the box content.
     */
    public function inner_custom_box() {

        // Render the bulk-comment-custom-box template
        include( dirname(__FILE__) . '/../views/bulk-comment-custom-box.php' );

    } // END public function inner_custom_box

    /**
     * When Bulk Update Comments, saves our custom data.
     */
    public function save_metadatas() {
        $test_tag = $_POST['test_tag'];
        $count = 0;

        foreach( $_POST['post_ids'] as $post_id ) {
            $post_id = (int) $post_id;
            
            if( $post_id != 0 ) {
                // Check the user's permissions.            
                if ( !current_user_can( 'edit_comment' ) )
                        die;

                update_comment_meta( $post_id, '_test_tag', $test_tag );
                
                $count++;
            }
        }
        
        printf( __( '%1d comments updated', 'tt' ), $count ); 
        
        die;
    } // END public function save_metadatas

    /**
    * Add bulk action on comment page
    */
    public function admin_footer() {

        // Render the bulk-comment-script template
        include( dirname(__FILE__) . '/../views/bulk-comment-scripts.php' );

    } // END public function admin_footer
} // END class Test_Tag_Bulk_Comment