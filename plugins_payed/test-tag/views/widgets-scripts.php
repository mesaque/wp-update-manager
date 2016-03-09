
<script type="text/javascript">
    (function($) {
        $('.test_tag_value_1:checked').each(function(){
            $(this).parents( ".widget" ).find('.widget-title').css('background', 'linear-gradient(to top, #6B9BAF, #F9F9F9) repeat scroll 0 0 #F1F1F1');
        })
        $('.test_tag_value_0').live('click', function(){
            $(this).parents( ".widget" ).find('.widget-title').css('background', 'none');
        })
        $('.test_tag_value_1').live('click', function(){
            $(this).parents( ".widget" ).find('.widget-title').css('background', 'linear-gradient(to top, #6B9BAF, #F9F9F9) repeat scroll 0 0 #F1F1F1');
        })
    })(jQuery);
</script>    
