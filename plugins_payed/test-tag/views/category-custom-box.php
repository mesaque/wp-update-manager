<?php       

// Add an nonce field so we can check for it later.
wp_nonce_field( 'test_tag_category_add_form_fields', 'test_tag_category_add_form_fields_nonce' );

/*
* retrieve "category_test_tag" meta.
*/
$category_test_tag = get_option( 'category_test_tag' );

if( isset( $category->term_id ) && isset( $category_test_tag[$category->term_id] ) )
    $test_tag = $category_test_tag[$category->term_id];
else
    $test_tag = 0;

?>
    <table class="form-table">
        <tbody>
            <tr class="form-field">
                <th valign="top" scope="row"><?php _e( 'Is it a Test ?', 'tt' ); ?></th>
                <td>
                    <input type="radio" name="test_tag" id="test_tag-1" <?php checked( $test_tag, 1 ); ?> value="1" style="width: auto;" />
                    <label id="test_tag_label-1" for="test_tag-1" style="display: inline;"><?php _e( 'yes', 'tt' ); ?></label>
                    <input type="radio" name="test_tag" id="test_tag-0" <?php checked( $test_tag, 0 ); ?> value="0" style="width: auto;" />
                    <label id="test_tag_label-0" for="test_tag-0" style="display: inline;"><?php _e( 'no', 'tt' ); ?></label>
                </td>
            </tr>
        </tbody>
    </table>    
