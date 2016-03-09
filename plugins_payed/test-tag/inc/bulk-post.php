<?php

class Test_Tag_Bulk_Post {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_action( 'wp_ajax_get_test_tag_bulk_posts_inner_custom_box', array( &$this, 'inner_custom_box' ) ); 
        add_action( 'wp_ajax_update_test_tag_bulk_posts', array( &$this, 'save_metadatas' ) );
        
        add_action( 'admin_footer-edit.php', array( &$this, 'admin_footer' ) );
        add_action( 'admin_footer-upload.php', array( &$this, 'admin_footer' ) );
    } // END public function __construct

    /**
     * Prints the box content.
     */
    public function inner_custom_box() {

        // Render the bulk-post-custom-box template
        include( dirname(__FILE__) . '/../views/bulk-post-custom-box.php' );
        
    } // END public function inner_custom_box

    /**
     * When Bulk Update Posts, saves our custom data.
     */
    public function save_metadatas() {
        $test_tag = $_POST['test_tag'];
        $count = 0;

        foreach( $_POST['post_ids'] as $post_id ) {
            $post_id = (int) $post_id;
            
            if( $post_id != 0 ) {
                // Check the user's permissions.

                $post = get_post( $post_id );
                $post_type = $post->post_type;
                
                // Check the user's permissions.
                if ( 'page' == $post_type ) {
                    if ( ! current_user_can( 'edit_page', $post_id ) )
                        die;
                } elseif ( 'post' == $post_type ) {
                    if ( ! current_user_can( 'edit_post', $post_id ) )
                        die;
                } elseif ( 'attachment' == $post_type ) {
                    if ( ! current_user_can( 'upload_files', $post_id ) )
                        die;
                } else { // custom
                    if ( ! current_user_can( 'edit_post', $post_id ) )
                        die;
                }

                update_post_meta( $post_id, '_test_tag', $test_tag );
                
                $count++;
            }
        }
        
        printf( __( '%1d posts updated', 'tt' ), $count ); 
        
        die;
    } // END public function save_metadatas

    /**
    * Add bulk action on post page
    */
    public function admin_footer() {

        // Render the bulk-post-scripts template
        include( dirname(__FILE__) . '/../views/bulk-post-scripts.php' );
        
    } // END public function admin_footer
} // END class Test_Tag_Bulk_Post