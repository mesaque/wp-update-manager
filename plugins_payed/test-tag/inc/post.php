<?php

class Test_Tag_Post {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_action( 'add_meta_boxes', array( &$this, 'add_custom_box' ) );

        foreach ( get_post_types() as $screen ) {
            add_filter('manage_' . $screen . 's_columns', array( &$this, 'columns' ));
            add_action('manage_' . $screen . 's_custom_column', array( &$this, 'show_columns' ));
        }
        add_filter('manage_media_columns', array( &$this, 'columns' ));
        add_action('manage_media_custom_column', array( &$this, 'show_columns' ));
        
        add_action( 'save_post', array( &$this, 'save_metadatas' ), 10, 1 );
        add_action( 'edit_attachment', array( &$this, 'save_metadatas' ), 10, 1 );
    } // END public function __construct
    
    /**
     * Adds a box to the side column on the Post types edit screens.
     */
    public function add_custom_box() {

        foreach ( get_post_types() as $screen ) {
            add_meta_box(
                'test_tag_meta_box',
                __( 'Test Tag', 'tt' ),
                array( &$this, 'inner_custom_box' ),
                $screen,
                'side',
                'low'
            );
        }
        
    } // END public function add_custom_box

    /**
     * Prints the box content.
     * 
     * @param WP_Post $post The object for the current post/page.
     */
    public function inner_custom_box( $post ) {

        // Render the post-custom-box template
        include( dirname(__FILE__) . '/../views/post-custom-box.php' );
        
    } // END public function inner_custom_box

    /**
     * Add Test Tag column to posts.
     * 
     * @param array $columns A list of columns.
     * @return array A list of columns 
     */    
    public function columns( $columns ) {
        $columns['test_tag'] = __( 'Test Tag', 'tt' );
        return $columns;
    } // END public function columns

    /**
     * Add Test Tag column value to posts.
     * 
     * @param string $column The column name.
     */    
    public function show_columns( $column ) {
        global $post;
        if( $column == 'test_tag' ) {
            $test_tag = get_post_meta( $post->ID, '_test_tag', true );
            print $test_tag == 1 ? __( 'Test', 'tt' ) : '';
        }
    } // END public function show_columns 

    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_metadatas( $post_id ) {
        
        /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_post can be triggered at other times.
        */
        // Check if our nonce is set.
        if ( ! isset( $_POST['test_tag_inner_custom_box_nonce'] ) )
            return $post_id;

        $nonce = $_POST['test_tag_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'test_tag_inner_custom_box' ) )
            return $post_id;
        
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return $post_id;

        $post = get_post( $post_id );
        $post_type = $post->post_type;
        
        // Check the user's permissions.
        if ( 'page' == $post_type ) {
            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;
        } elseif ( 'post' == $post_type ) {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        } elseif ( 'attachment' == $post_type ) {
            if ( ! current_user_can( 'upload_files', $post_id ) )
                return $post_id;
        } else { // custom
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        $test_tag = $_POST['test_tag'];
        
        update_post_meta( $post_id, '_test_tag', $test_tag );
    } // END public function save_metadatas
} // END class Test_Tag_Post