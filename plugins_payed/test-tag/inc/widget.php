<?php

class Test_Tag_Widget {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_action( 'in_widget_form', array( &$this, 'inner_custom_box' ), 100, 3 );
        add_filter( 'widget_update_callback', array( &$this, 'update_callback' ), 60, 2 );
        add_filter( 'current_screen', array( &$this, 'widgets_screen' ) );
    } // END public function __construct

    /**
    * Add Scripts on widgets page
    */
    public function widgets_screen( $screen ) {
        if ( isset( $screen->id ) && $screen->id == 'widgets' ) {
            add_action( 'admin_footer', array( &$this, 'admin_footer' ) );
        }
        return $screen;
    } // END public function comment_screen
    
    /**
     * Adds Test Tag form field to Widget
     * 
     * @param $widget
     * @param $return
     * @param $instance
     * @return array
     */
    public function inner_custom_box( $widget, $return, $instance ) {

        // Render the widget-custom-box template
        include( dirname(__FILE__) . '/../views/widget-custom-box.php' );

        $return = null;

        return array( $widget, $return, $instance );

    } // END public function options

    /**
    * Update widget metas
    * 
    * @param mixed $instance
    * @param mixed $new_instance
    * @return array
    */
    public function update_callback( $instance, $new_instance ) {
        if( !isset( $new_instance['test_tag'] ) ) {
            $new_instance['test_tag'] = 0;
        }
        
        return $new_instance;
    } // END public function update_callback

    /**
    * Add scripts on widgets page
    */
    public function admin_footer() {

        // Render the widgets-scripts template
        include( dirname(__FILE__) . '/../views/widgets-scripts.php' );
        
    } // END public function admin_footer
} // END class Test_Tag_Widget