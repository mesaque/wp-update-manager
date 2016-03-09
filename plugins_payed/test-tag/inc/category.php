<?php

class Test_Tag_Category {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_action('category_add_form_fields', array( &$this, 'add_form_fields' ), 10, 1);
        add_action('category_edit_form_fields', array( &$this, 'add_form_fields' ), 10, 1);
        
        add_action('created_category', array( &$this, 'save_metadatas' ), 10, 1);    
        add_action('edited_category', array( &$this, 'save_metadatas' ), 10, 1);    
        
        add_filter( 'manage_edit-category_columns', array( &$this, 'columns' ) );
        add_filter( 'manage_category_custom_column', array( &$this, 'show_column' ), 10, 3 );
    } // END public function __construct
    
    /**
    * Adds a box to the main column on the Category edit screens.
    * 
    * @param Object $category The category to be edited.
    */
    public function add_form_fields( $category ) {

        // Render the category-custom-box template
        include( dirname(__FILE__) . '/../views/category-custom-box.php' );

    } // END public function add_form_fields
 
    /**
     * When the Category is saved, saves our custom datas.
     *
     * @param int $term_id The ID of the category being saved.
     */
    public function save_metadatas( $term_id ) {

        /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_post can be triggered at other times.
        */
        // Check if our nonce is set.
        if ( ! isset( $_POST['test_tag_category_add_form_fields_nonce'] ) )
            return $term_id;

        $nonce = $_POST['test_tag_category_add_form_fields_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'test_tag_category_add_form_fields' ) )
            return $term_id;
        
        if ( ! current_user_can( 'manage_categories', $term_id ) )
            return $term_id;

        /* OK, its safe for us to save the data now. */

        $test_tag = $_POST['test_tag'];
        $category_test_tag = get_option( 'category_test_tag' );
        
        $category_test_tag[$term_id] = $test_tag;
        update_option( 'category_test_tag', $category_test_tag );
    } // END public function save_metadatas

    /**
     * Add Test Tag column to Categories.
     * 
     * @param array $columns A list of columns.
     * @return array A list of columns 
     */    
    public function columns( $columns ) {
        $columns['test_tag'] = __( 'Test Tag', 'tt' );
        return $columns;
    } // END public function columns

    /**
     * Add Test Tag column to Categories.
     * 
     * @param mixed $arg.
     * @param string $column The column name.
     * @param string $category_id The Category ID.
     * @return string The Test Tag Value.
     */    
    public function show_column( $arg, $column, $category_id ){
        if( $column == 'test_tag' ){
            $category_test_tag = get_option( 'category_test_tag' );
            
            if( isset( $category_test_tag[$category_id] ) ) {
                print $category_test_tag[$category_id] == 1 ? __( 'Test', 'tt' ) : ''; 
            }
        }
    } // END public function show_column
} // END class Test_Tag_Category