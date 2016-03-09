
<h1><?php _e( 'Test Tag', 'tt' ) ?></h1>
<?php 

/**
* Add New Form
*/   

?>
<form id="delete_test_tagged_content">
    <input value="<?php _e( 'Delete Test Content', 'tt' ); ?>" type="submit" />
</form>
<div id="progression_container" class="meter blue">
    <span id="progression_bar" style="width: 0;"></span>
</div>
<div id="result_container">
    <h2><?php _e( 'Result', 'tt' ); ?></h2>
    <h3 id="result_type"><?php _e( 'Posts' ); ?> <span id="percent">0%</span></h3>
    <ol id="result" class="compiled" reversed></ol>
</div>
<script type="text/javascript">
    (function($) {
        $(function() {
            var offset = 0;
            var max_num_pages = 0;
            
            $('#delete_test_tagged_content').submit(function(event) {
                event.preventDefault();
                
                if (confirm('<?php _e( 'Are you sure you want to REMOVE PERMANENTLY all "Test Tagged" content ?', 'tt' ) ?>')) {
                    $('#progression_container, #result_container').show();
                    $('#result_type').html('<?php _e( 'Prossesing Posts <span id="percent">0%</span>', 'tt' ); ?>');
                    delete_posts();
                }
                
                return false;
            });
            
            function delete_posts() {
                $.ajax({
                    url: '<?php print admin_url( 'admin-ajax.php' ); ?>',
                    type: 'POST',
                    data: {
                        action: 'delete_posts_test_tagged',
                        offset: offset,
                        max_num_pages: max_num_pages
                    },
                    success: function(result) {
                        var progression = jQuery.parseJSON( result );
                        
                        if( progression.max_num_pages != 0 ) {
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.post);
                            
                            offset++;
                            max_num_pages = progression.max_num_pages;
                            
                            delete_posts();
                        } else {
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.post);
                            $('#result_type').html('<?php _e( 'Prossesing Categories <span id="percent">0%</span>', 'tt' ); ?>');
                                                                
                            offset = 0;
                            max_num_pages = 0;
          
                            delete_categories();
                        }
                    }
                });                                            
            }    
            
            function delete_categories() {
                $.ajax({
                    url: '<?php print admin_url( 'admin-ajax.php' ); ?>',
                    type: 'POST',
                    data: {
                        action: 'delete_categories_test_tagged',
                        offset: offset,
                        max_num_pages: max_num_pages
                    },
                    success: function(result) {
                        var progression = jQuery.parseJSON( result );
                        
                        if( progression.max_num_pages != 0 ) {
                            
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.category);
                            
                            offset++;
                            max_num_pages = progression.max_num_pages;
                            
                            delete_categories();
                        } else {
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.category);
                            $('#result_type').html('<?php _e( 'Prossesing Widgets <span id="percent">0%</span>', 'tt' ); ?>');
                            
                            offset = 0;
                            max_num_pages = 0;
                            
                            delete_widgets();
                        }
                    }
                });                                            
            }    
            
            function delete_widgets() {
                $.ajax({
                    url: '<?php print admin_url( 'admin-ajax.php' ); ?>',
                    type: 'POST',
                    data: {
                        action: 'delete_widgets_test_tagged',
                        offset: offset,
                        max_num_pages: max_num_pages
                    },
                    success: function(result) {
                        var progression = jQuery.parseJSON( result );
                        
                        if( progression.max_num_pages != 0 ) {
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.widget);
                            
                            offset++;
                            max_num_pages = progression.max_num_pages;
                            
                            delete_widgets();
                        } else {
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.widget);
                            $('#result_type').html('<?php _e( 'Prossesing Comments <span id="percent">0%</span>', 'tt' ); ?>');
                            
                            offset = 0;
                            max_num_pages = 0;
                            
                            delete_comments();
                        }
                    }
                });                                            
            }    
            
            function delete_comments() {
                $.ajax({
                    url: '<?php print admin_url( 'admin-ajax.php' ); ?>',
                    type: 'POST',
                    data: {
                        action: 'delete_comments_test_tagged',
                        offset: offset,
                        max_num_pages: max_num_pages
                    },
                    success: function(result) {
                        var progression = jQuery.parseJSON( result );
                        
                        if( progression.max_num_pages != 0 ) {
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.comment);
                            
                            offset++;
                            max_num_pages = progression.max_num_pages;
                            
                            delete_comments();
                        } else {
                            $('#progression_bar').css('width', progression.percent + '%');
                            $('#percent').html(progression.percent + '%');
                            $('#result').prepend(progression.comment);
                            $('#result').prepend('<li><?php _e( 'No more "Test Tagged" content Found !', 'tt' ); ?></li>');
                            $('.meter').addClass('noanim');
                            $('#result_type').html('<?php _e( 'Prossesing "test Tagged" Content <span style="color: green;">Finished !</span>', 'tt' ); ?>');
                            
                            offset = 0;
                            max_num_pages = 0;
                            
                            return false;
                        }
                    }
                });                                            
            }    
        });
    })(jQuery)
</script>
