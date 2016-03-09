
<a id="test_tag_form_container_launcher" href="#test_tag_form_container" class="nyroModal"></a>    
<div id="test_tag_form_super_container"><div id="test_tag_form_container"></div></div>
<script type="text/javascript">
    (function($) {
        $(function() {
            $('<option>').val('test_tag').text('<?php _e('Test Tag', 'tt')?>').appendTo("select[name='action']");
            $('<option>').val('test_tag').text('<?php _e('Test Tag', 'tt')?>').appendTo("select[name='action2']");
            $('#doaction').click(function(event) {
                if($('select[name="action"]').val() == 'test_tag') {
                     event.preventDefault();
                     build_test_tag_form();
                     return false;
                }
            })
            $('#doaction2').click(function(event) {
                if($('select[name="action2"]').val() == 'test_tag') {
                     event.preventDefault();
                     build_test_tag_form();
                     return false;
                }
            })
            function build_test_tag_form() {
                var post_ids = [];
                $('[id^=cb-select-]:checked').each(function() {
                    post_ids.push($(this).val());
                }); 
                
                if( post_ids.length > 0 ) {
                    $.ajax({
                        url: '<?php print admin_url( 'admin-ajax.php' ); ?>',
                        type: 'POST',
                        data: {
                            action: 'get_test_tag_bulk_comments_inner_custom_box',
                            post_ids: post_ids
                        },
                        success: function(result) {
                            $('#test_tag_form_container').html(result).show();
                            $('#test_tag_form_container_launcher').nyroModal().click();
                        }
                    });

                    $('#test_tag_form').unbind( "submit" ).live('submit', function(event) {
                        event.preventDefault();
                        var test_tag = $('[name=test_tag]:checked').val();
                        $.ajax({
                            url: '<?php print admin_url( 'admin-ajax.php' ); ?>',
                            type: 'POST',
                            data: {
                                action: 'update_test_tag_bulk_comments',
                                post_ids: post_ids,
                                test_tag: test_tag
                            },
                            success: function(result) {
                                $('#test_tag_result').html(result);
                            }
                        });                                            
                        return false;
                    });    
                }
            }
        });
    })(jQuery)
</script>
<style>
    #test_tag {
        width: 5%;
    }
    
    .test_tag_bulk_container {
        width: 300px;
        float: left;
    }
    
    .test_tag_bulk_container ol {
        overflow-y: auto; 
        height: 190px;
    }
    
    .test_tag_bulk_container ol li {
        margin-left: 20px;
    }
    
    #test_tag_result {
        color: rgb(0, 128, 0); 
        text-align: center; 
        position: absolute; 
        top: 80px; 
        right: 90px;
    }
    
    #test_tag_form {
        position: absolute; 
        top: 90px; 
        right: 100px;
    }
    
    #test_tag_form_super_container,
    #test_tag_form_container_launcher {
        display: none;
    }
    
    #test_tag_form_container {
        width: 600px; 
        display: none;
    }
</style>
