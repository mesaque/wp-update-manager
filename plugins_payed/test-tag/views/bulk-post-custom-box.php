<?php
// Add an nonce field so we can check for it later.
wp_nonce_field( 'test_tag_bulk_posts_inner_custom_box', 'test_tag_bulk_posts_inner_custom_box_nonce' );

?>
<div class="test_tag_bulk_container">
    <h2><?php _e( 'Selected Posts', 'tt' ); ?></h2>
    <ol>
    <?php
    foreach( $_POST['post_ids'] as $post_id ) {
        $post_id = (int) $post_id;
        
        if( $post_id != 0 ) {
            $post = get_post( $post_id );
            ?>
            <li><?php print $post->post_title . ' ('  . $post_id . ')'; ?></li>    
            <?php
        }    
    }
    ?>
    </ol>
</div>
<div class="test_tag_bulk_container">
    <div id="test_tag_result"></div>
    <form id="test_tag_form">
        <h2><?php _e( 'Is it a Test ?', 'tt' ); ?></h2>
        <input type="radio" name="test_tag" id="test_tag-1" value="1" />
        <label id="test_tag_label-1" for="test_tag-1"><?php _e( 'yes', 'tt' ); ?></label>
        <input type="radio" name="test_tag" id="test_tag-0" value="0" />
        <label id="test_tag_label-0" for="test_tag-0"><?php _e( 'no', 'tt' ); ?></label>
        <input id="test_tag_form_submit" type="submit" value="<?php _e( 'Update', 'tt' ); ?>" />
    </form>
</div>
<?php

die;