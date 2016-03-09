<?php

class Test_Tag_Delete {
    /**
     * Construct the plugin object
     */
    public function __construct() {
        // register actions & filters
        add_action( 'wp_ajax_delete_posts_test_tagged', array( &$this, 'delete_posts_test_tagged' ) );
        add_action( 'wp_ajax_delete_comments_test_tagged', array( &$this, 'delete_comments_test_tagged' ) );
        add_action( 'wp_ajax_delete_categories_test_tagged', array( &$this, 'delete_categories_test_tagged' ) );
        add_action( 'wp_ajax_delete_widgets_test_tagged', array( &$this, 'delete_widgets_test_tagged' ) );
    } // END public function __construct
    
    /**
     * Delete test tagged posts.
     */
    public function delete_posts_test_tagged() {
        
        $offset = (int)$_POST['offset'];
        $max_num_pages = (int)$_POST['max_num_pages'];
        
        /**
        * Retrieve posts, pages, custom_posts, attachments where "_test_tag" is defined
        */
        $args = array(
            'posts_per_page' => 1,
            'post_type' => 'any',
            'post_status' => 'any',
            'paged' => 1,
            'meta_query' => array(
                array(
                    'key' => '_test_tag',
                    'value' => 1,
                )
            )
        );
        $query = new WP_Query($args);
        
        if( $query->max_num_pages == 0 ) {
            $result['post'] = __( '<li>No More Post "Test Tagged" Found !</li>', 'tt' );
            $result['max_num_pages'] = 0;
            $result['percent'] = 100;
            print json_encode( $result ); 
            
            die;
        }
        
        if( $max_num_pages == 0 ) {
            $max_num_pages = $query->max_num_pages;
        }
        
        $result = array();
        if( $query->have_posts() ) : $query->the_post();
            $post_type = $query->post->post_type;
            $post_id = get_the_ID();
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
            
            $result['post'] = sprintf( __( '<li>Post: %1s (%2d) <span style="color: green;">deleted</span></li>', 'tt' ), get_the_title(), $post_id );
            $result['max_num_pages'] = $max_num_pages;
            $result['percent'] = (int) ( 100 * ($offset + 1) / $max_num_pages );
            
            /**
            * Delete post
            */
            wp_delete_post( $post_id, true );
        endif;
        
        wp_reset_postdata();

        print json_encode( $result ); 
        
        die;
    } // END public function delete_posts_test_tagged
  
    /**
     * Delete test tagged comments.
     */
    public function delete_comments_test_tagged() {
        
        // Check the user's permissions.            
        if ( !current_user_can( 'edit_comment' ) )
            die;
        
        $offset = (int)$_POST['offset'];
        $max_num_pages = (int)$_POST['max_num_pages'];
        
        /**
        * Retrieve comments where "_test_tag" is defined
        */
        $args = array(
            'meta_query' => array(
                array(
                    'key' => '_test_tag',
                    'value' => 1,
                )
            )
        );
        $comments_query = new WP_Comment_Query($args);
        $comments = $comments_query->query( $args );
        
        if( count( $comments ) == 0 ) {
            $result['comment'] = __( '<li>No More Comments "Test Tagged" Found !</li>', 'tt' );
            $result['max_num_pages'] = 0;
            $result['percent'] = 100;
            print json_encode( $result ); 
            
            die;
        }
        
        if( $max_num_pages == 0 ) {
            $max_num_pages = count( $comments );
        }
        
        $result = array();
        if( !empty( $comments ) ) : $comment = $comments[0];
            $result['comment'] = sprintf( __( '<li>Comment: %1s (%2d) <span style="color: green;">deleted</span></li>', 'tt' ), wordwrap( strip_tags( $comment->comment_content ), 40 ), $comment->comment_ID );
            $result['max_num_pages'] = $max_num_pages;
            $result['percent'] = (int) ( 100 * ($offset + 1) / $max_num_pages );
            
            /**
            * Delete comment
            */
            wp_delete_comment( $comment->comment_ID, true );
        endif;
        
        wp_reset_postdata();

        print json_encode( $result ); 
        
        die;
    } // END public function delete_comments_test_tagged
 
    /**
     * Delete test tagged categories.
     */
    public function delete_categories_test_tagged() {
        
        $offset = (int)$_POST['offset'];
        $max_num_pages = (int)$_POST['max_num_pages'];

        /**
        * Retrieve categories where "_test_tag" is defined
        */
        $category_test_tag = get_option( 'category_test_tag' );
        
        if( !isset( $category_test_tag ) || empty( $category_test_tag ) ) {
            /**
            * Delete option "category_test_tag"
            */
            delete_option( 'category_test_tag' );
            
            $result['category'] = __( '<li>No More Category "Test Tagged" Found !</li>', 'tt' );
            $result['max_num_pages'] = 0;
            $result['percent'] = 100;
            
            print json_encode( $result ); 
            
            die;
        }
        
        if( $max_num_pages == 0 ) {
            $max_num_pages = count( (array)$category_test_tag );
        }
            
        foreach( $category_test_tag as $key => $tag ) {
            if( $tag == true ) {
                $category = get_category( $key );
                if( isset( $category ) ) {
                    $term_id = $category->term_id;
                    
                    if ( ! current_user_can( 'manage_categories', $term_id ) )
                        return $term_id;
                        
                    $result['category'] = sprintf( __( '<li>Category: %1s (%2d) <span style="color: green;">deleted</span></li>', 'tt' ), $category->name, $term_id );
                    $result['max_num_pages'] = $max_num_pages;
                    $result['percent'] = (int) ( 100 * ($offset + 1) / $max_num_pages );
                    
                    /**
                    * Delete category
                    */
                    wp_delete_category( $key );
                    
                    unset( $category_test_tag[$key] );
                    
                    update_option( 'category_test_tag', $category_test_tag );
                    
                    print json_encode( $result );
                    die;  
                }
            }
        }
    } // END public function delete_categories_test_tagged
 
    /**
     * Delete test tagged widgets.
     */
    function delete_widgets_test_tagged() {
        global $wpdb;
        
        $offset = (int)$_POST['offset'];
        $max_num_pages = (int)$_POST['max_num_pages'];

        /**
        * Retrieve widgets where "_test_tag" is defined and sidebars
        */
        $sidebars_widgets = get_option( 'sidebars_widgets' );
        $widgets_types = $wpdb->get_results(  
            "
              SELECT * 
              FROM {$wpdb->options} 
              WHERE option_name LIKE 'widget_%'
            "
        );
        
        if( $max_num_pages == 0 ) {
            foreach( $widgets_types as $key1 => $widget_type ) {
                $widgets = unserialize( $widget_type->option_value );
                $short_type = str_replace( 'widget_', '', $widget_type->option_name );

                foreach( $widgets as $key2 => $widget ) {
                    if( $key2 != '_multiwidget' ) {
                        if( array_key_exists( 'test_tag', $widget ) ) {
                            $max_num_pages++;
                        }                
                    }
                }
            }
        }
        
        $test_widgets = array();
        $result = array();
        foreach( $widgets_types as $key1 => $widget_type ) {
            $widgets = unserialize( $widget_type->option_value );
            $short_type = str_replace( 'widget_', '', $widget_type->option_name );

            foreach( $widgets as $key2 => $widget ) {
                if( $key2 != '_multiwidget' ) {
                    if( array_key_exists( 'test_tag', $widget ) ) {
                        $result['widget'] = sprintf( __( '<li>Widget: %1s (%2d) <span style="color: green;">deleted</span></li>', 'tt' ), $widget['title'], $short_type . '-' . $key2 );
                        $result['max_num_pages'] = $max_num_pages;
                        $result['percent'] = (int) ( 100 * ($offset + 1) / $max_num_pages );
                        
                        $test_widgets[] = $short_type . '-' . $key2;
                        
                        unset( $widgets[$key2] );
                        
                        /**
                        * Delete widget
                        */
                        update_option( $widget_type->option_name, $widgets );
                        
                        break;
                    }                
                }
            }
        }    
        
        foreach( $sidebars_widgets as $key1 => $sidebar ) {
            foreach( (array)$sidebar as $key2 => $widget ) {
                if( in_array( $widget, $test_widgets ) ) {
                    /**
                    * Delete widget in sidebars
                    */
                     unset( $sidebars_widgets[$key1][$key2] );
                }
            }
        }

        if( empty( $test_widgets ) ) {
            $result['widget'] = __( '<li>No More Widget "Test Tagged" Found !</li>', 'tt' );
            $result['max_num_pages'] = 0;
            $result['percent'] = 100;
            print json_encode( $result ); 
            
            die;
        }
            
        /**
        * Update sidebars
        */
        update_option( 'sidebars_widgets', $sidebars_widgets );
        
        print json_encode( $result );
        die;  
    } // END function delete_widgets_test_tagged
} // END class Test_Tag_Delete