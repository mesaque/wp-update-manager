<?php

/**
* Add an nonce field so we can check for it later.
*/
wp_nonce_field( 'test_tag_inner_custom_box', 'test_tag_inner_custom_box_nonce' );

/*
* retrieve "_test_tag" meta.
*/
$test_tag = get_post_meta( $post->ID, '_test_tag', true );

if( !isset( $test_tag ) || empty( $test_tag ) )
    $test_tag = 0;
?>
    <div><?php _e( 'Is it a Test ?', 'tt' ); ?></div>
    <input type="radio" name="test_tag" id="test_tag-1" <?php checked( $test_tag, 1 ); ?> value="1" />
    <label class="test_tag_label" id="test_tag_label-1" for="test_tag-1"><?php _e( 'yes', 'tt' ); ?></label>
    <input type="radio" name="test_tag" id="test_tag-0" <?php checked( $test_tag, 0 ); ?> value="0" />
    <label class="test_tag_label" id="test_tag_label-0" for="test_tag-0"><?php _e( 'no', 'tt' ); ?></label>

