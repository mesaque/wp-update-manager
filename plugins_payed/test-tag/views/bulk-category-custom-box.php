<?php
        
// Add an nonce field so we can check for it later.
wp_nonce_field( 'test_tag_bulk_categories_inner_custom_box', 'test_tag_bulk_categories_inner_custom_box_nonce' );

?>
<div class="test_tag_bulk_container">
    <h2><?php _e( 'Selected Categories', 'tt' ); ?></h2>
    <ol>
    <?php
    foreach( $_POST['cat_ids'] as $cat_id ) {
        $cat_id = (int) $cat_id;
        
        if( $cat_id != 0 ) {
            $category = get_term( $cat_id, 'category' );
            ?>
            <li><?php print $category->name . ' ('  . $cat_id . ')'; ?></li>    
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
        <input type="radio" name="test_tag_bulk" id="test_tag_bulk-1" value="1" />
        <label id="test_tag_label-1es" for="test_tag_bulk-1"><?php _e( 'yes', 'tt' ); ?></label>
        <input type="radio" name="test_tag_bulk" id="test_tag_bulk-0" value="0" />
        <label id="test_tag_label-0" for="test_tag_bulk-0"><?php _e( 'no', 'tt' ); ?></label>
        <input id="test_tag_form_submit" type="submit" value="<?php _e( 'Update', 'tt' ); ?>" />
    </form>
</div>
<?php

die;
