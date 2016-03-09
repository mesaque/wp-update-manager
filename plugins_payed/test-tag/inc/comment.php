<?php

class Test_Tag_Comment {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_action( 'add_meta_boxes', array( &$this, 'add_custom_box' ) );
        add_filter( 'comment_save_pre', array( &$this, 'save_metadatas' ) );
    } // END public function __construct

    /**
     * Adds a box to the main column on the Comment edit screen.
     */
    public function add_custom_box() {

        add_meta_box(
            'test_tag',
            __( 'Test Tag', 'tt' ),
            array( &$this, 'inner_custom_box' ),
            'comment',
            'normal',
            'low'
        );
        
    } // END public function add_custom_box

    /**
     * When the comment is saved, saves our custom data.
     *
     * @param int $comment_id The ID of the comment being saved.
     */
    public function save_metadatas( $comment_content ) {
            
        $comment_ID = absint($_POST['comment_ID']);

        /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_comment can be triggered at other times.
        */
        // Check if our nonce is set.
        if ( ! isset( $_POST['test_tag_inner_custom_box_nonce'] ) )
            return $comment_ID;

        $nonce = $_POST['test_tag_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'test_tag_inner_custom_box' ) )
            return $comment_ID;
        
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return $comment_ID;
        
        // Check the user's permissions.            
        if ( !current_user_can( 'edit_comment' ) )
            die;
        
        $test_tag = $_POST['test_tag'];
        
        update_comment_meta( $comment_ID, '_test_tag', $test_tag );
        
        return $comment_content;
    } // END public function save_metadatas

    /**
     * Prints the box content.
     * 
     * @param object $comment The object for the current comment/page.
     */
    public function inner_custom_box( $comment ) {

        // Render the comment-custom-box template
        include( dirname(__FILE__) . '/../views/comment-custom-box.php' );

    } // END public function inner_custom_box
} // END class Test_Tag_Comment