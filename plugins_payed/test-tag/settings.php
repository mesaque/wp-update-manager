<?php

class Test_Tag_Settings {
	/**
	 * Construct the plugin object
	 */
	public function __construct() {
		// register actions
        add_action( 'plugins_loaded', array( &$this, 'init_textdomain' ) );
        add_action( 'admin_menu', array( &$this, 'add_menu' ) );
	} // END public function __construct

    /**
    * Init textdomain
    */
    public function init_textdomain() {
        
        load_plugin_textdomain('tt', false, basename( dirname( __FILE__ ) ) . '/lang' ); 
        
    } // END public function init_textdomain
    
    /**
     * add a menu
     */		
    public function add_menu()
    {
        /**
        * Add Test Tag menu entry in "Tools" called "Test Tag"
        */
        $plugin_page = add_management_page( 
            __( 'Test Tag', 'tt' ), 
            __( 'Test Tag', 'tt' ), 
            'manage_options', 
            'test_tag', 
            array( &$this, 'tool_page' )
        );  
        
        /**
        * Add some style to our page
        */
        add_action( 'admin_print_styles-' . $plugin_page, array( &$this, 'print_style' ) );

    } // END public function add_menu

    /**
    * Display the Test Tag Tool Page
    */
    public function tool_page() {

        // Render the tools template
        include( dirname(__FILE__) . '/views/tools.php' );
        
    } // END public function tool_page
    
    /**
    * Add Test Tag page some style
    */
    public function print_style() {

        // Render the style template
        include( dirname(__FILE__) . '/views/style.php' );

    } // END public function print_style
} // END class Test_Tag_Settings