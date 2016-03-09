<?php

if( isset( $instance['test_tag'] ) )
    $test_tag = $instance['test_tag'];
else 
    $test_tag = 0;

$field_id = $widget->get_field_id('test_tag');
$field_name = $widget->get_field_name('test_tag');
?>
    <div><?php _e( 'Is it a Test ?', 'tt' ); ?></div>
    <input class="test_tag_value_1" type="radio" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>-1" <?php checked( $test_tag, 1 ); ?> value="1" />
    <label class="test_tag_label" id="test_tag_label-1" for="<?php echo $field_id; ?>-1"><?php _e( 'yes', 'tt' ); ?></label>
    <input class="test_tag_value_0" type="radio" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>-0" <?php checked( $test_tag, 0 ); ?> value="0" />
    <label class="test_tag_label" id="test_tag_label-0" for="<?php echo $field_id; ?>-0"><?php _e( 'no', 'tt' ); ?></label>
